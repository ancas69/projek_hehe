import tkinter as tk
from tkinter import Tk, ttk, filedialog, messagebox, scrolledtext
import threading
import time
import tkinter
import pandas as pd
import pyautogui
import cv2
import numpy as np
import os
import json
from datetime import datetime
import keyboard
import mss
import re
import pynput
from pynput.mouse import Listener, Controller
import sys
import math
from tkinter import PhotoImage

class RegionSelector:
    """Custom region selector using pynput"""
    def __init__(self, mode="region"):
        self.start_x = None
        self.start_y = None
        self.end_x = None
        self.end_y = None
        self.selecting = False
        self.completed = False
        self.mouse = Controller()
        self.listener = None
        self.mode = mode
        
    def start_selection(self, timeout=30):
        self.start_x = None
        self.start_y = None
        self.end_x = None
        self.end_y = None
        self.selecting = False
        self.completed = False
        
        # Stop listener lama jika masih ada
        if self.listener is not None:
            try:
                self.listener.stop()
            except:
                pass
            self.listener = None
        
        self.listener = Listener(on_click=self.on_click)
        self.listener.start()
        
        # Tunggu dengan timeout agar tidak menggantung selamanya
        start_time = time.time()
        while not self.completed:
            if time.time() - start_time > timeout:
                # Timeout - hentikan listener
                try:
                    self.listener.stop()
                except:
                    pass
                self.listener = None
                return None
            time.sleep(0.05)
        
        # Pastikan listener berhenti setelah selesai
        try:
            self.listener.stop()
        except:
            pass
        self.listener = None
        
        return self.get_region()
    
    def on_click(self, x, y, button, pressed):
        try:
            if self.mode == "point":
                if button == pynput.mouse.Button.left and pressed:
                    self.start_x = x
                    self.start_y = y
                    self.end_x = x
                    self.end_y = y
                    self.completed = True
                    return False  # stop listener
                elif button == pynput.mouse.Button.right and pressed:
                    # Batal - set completed agar loop berhenti
                    self.start_x = None
                    self.start_y = None
                    self.end_x = None
                    self.end_y = None
                    self.completed = True
                    return False
            else:
                if button == pynput.mouse.Button.left:
                    if pressed and not self.selecting:
                        self.start_x = x
                        self.start_y = y
                        self.selecting = True
                    elif not pressed and self.selecting:
                        self.end_x = x
                        self.end_y = y
                        self.selecting = False
                        self.completed = True
                        return False
                elif button == pynput.mouse.Button.right and self.selecting:
                    self.selecting = False
                    self.completed = True
                    return False
        except Exception as e:
            # Safety: apapun error, hentikan listener
            self.completed = True
            return False
    
    def get_region(self):
        if self.start_x is None or self.end_x is None:
            return None
        
        if self.mode == "point":
            return (self.start_x, self.start_y, 1, 1)
        else:
            x = min(self.start_x, self.end_x)
            y = min(self.start_y, self.end_y)
            width = abs(self.end_x - self.start_x)
            height = abs(self.end_y - self.start_y)
            return (x, y, width, height)

class Step:
    def __init__(self, step_type="click", name="", position=None, delay=0.5, 
                 text="", key_combination="", scroll_amount=0, drag_to=None,
                 type_mode="all"):
        self.step_type = step_type
        self.name = name
        self.position = position
        self.delay = delay
        self.text = text
        self.key_combination = key_combination
        self.scroll_amount = scroll_amount
        self.drag_to = drag_to
        self.enabled = True
        self.type_mode = type_mode  # "all" atau "numbers_only"

class AutomationApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Aplikasi Otomasi - by Acs.Wbc")

        # logo aplikasi
        icon = PhotoImage(file='logo.png')
        root.iconphoto(True, icon)
        
        # Get screen size
        screen_width = self.root.winfo_screenwidth()
        screen_height = self.root.winfo_screenheight()
        
        # Set window size - 85% of screen or minimum 1400x900
        window_width = max(500, int(screen_width * 0.85))
        window_height = max(900, int(screen_height * 0.85))
        
        self.root.geometry(f"{window_width}x{window_height}")
        
        self.root.resizable(True, True)
        
        # Variables
        self.excel_file_path = tk.StringVar()
        self.config_file_path = tk.StringVar()
        
        # Steps
        self.steps = []
        self.step_counter = 0
        
        # Excel mapping
        self.excel_columns = []
        self.column_mapping = {}
        
        # Running state
        self.is_running = False
        self.data_list = []
        self.results = []
        
        # Region selector
        self.region_selector = RegionSelector(mode="point")
        
        # Stop shortcut
        self.stop_hotkey = "ctrl+shift+x"
        keyboard.add_hotkey(self.stop_hotkey, self.stop_automation)

        # Start trigger key
        self.start_hotkey = "ctrl+shift+s"  # Default
        keyboard.add_hotkey(self.start_hotkey, self.trigger_start_automation)
        
        # Start delay (detik)
        self.start_delay = 5  # Default 5 detik
        
        # Step-by-step mode (per step)
        self.is_step_mode_running = False
        self.step_mode_data = []
        self.step_mode_total_data = 0
        self.current_data_index = 0
        self.current_step_index = 0
        self.step_mode_results = []
        
        # Custom group step settings
        self.custom_groups = []  # List of lists, misal: [[1], [2,3,4], [5]]
        self.use_custom_groups = False
        
        # UI Components
        self.step_listbox = None
        
        # Load config
        self.load_config()
        
        # Setup UI
        self.setup_ui()
        self.center_window()
        
        # Safety
        pyautogui.FAILSAFE = True
        pyautogui.PAUSE = 0.3
        
        # Show shortcut info
        self.log(f"Shortcut Stop: {self.stop_hotkey.upper()}", 'info')
        self.log(f"Shortcut Start: {self.start_hotkey.upper()} (tekan untuk memulai)", 'info')

    def center_window(self):
        self.root.update_idletasks()
        width = self.root.winfo_width()
        height = self.root.winfo_height()
        x = (self.root.winfo_screenwidth() // 2) - (width // 2)
        y = (self.root.winfo_screenheight() // 2) - (height // 2)
        self.root.geometry(f'{width}x{height}+{x}+{y}')

    def open_donasi(self):
        """Membuka link Saweria di browser"""
        import webbrowser
        webbrowser.open('https://saweria.co/ancaswasetoy98')
        self.log("🌐 Membuka halaman donasi...", 'info')
    
    def setup_ui(self):
        # Main container with both vertical and horizontal scrollbars
        main_container = ttk.Frame(self.root)
        main_container.pack(fill=tk.BOTH, expand=True)
        
        # Create canvas with scrollbars
        self.canvas = tk.Canvas(main_container, highlightthickness=0)
        
        # Vertical scrollbar
        v_scrollbar = ttk.Scrollbar(main_container, orient=tk.VERTICAL, command=self.canvas.yview)
        v_scrollbar.pack(side=tk.RIGHT, fill=tk.Y)
        
        # Horizontal scrollbar
        h_scrollbar = ttk.Scrollbar(main_container, orient=tk.HORIZONTAL, command=self.canvas.xview)
        h_scrollbar.pack(side=tk.BOTTOM, fill=tk.X)
        
        self.canvas.configure(yscrollcommand=v_scrollbar.set, xscrollcommand=h_scrollbar.set)
        self.canvas.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        
        # Main frame inside canvas
        main_frame = ttk.Frame(self.canvas, padding="10")
        self.canvas_window = self.canvas.create_window((0, 0), window=main_frame, anchor=tk.NW)
        
        # Configure scroll region
        def configure_scroll_region(event):
            self.canvas.configure(scrollregion=self.canvas.bbox("all"))
            self.canvas.itemconfig(self.canvas_window, width=self.canvas.winfo_width())
        
        main_frame.bind("<Configure>", configure_scroll_region)
        
        # Bind canvas resize to update window width
        def on_canvas_configure(event):
            self.canvas.itemconfig(self.canvas_window, width=event.width)
        
        self.canvas.bind("<Configure>", on_canvas_configure)
        
        # Mouse wheel scrolling
        def on_mousewheel(event):
            self.canvas.yview_scroll(int(-1*(event.delta/120)), "units")
        
        def on_shift_mousewheel(event):
            self.canvas.xview_scroll(int(-1*(event.delta/120)), "units")
        
        self.canvas.bind_all("<MouseWheel>", on_mousewheel)
        self.canvas.bind_all("<Shift-MouseWheel>", on_shift_mousewheel)
        
        # Title
        # Title dengan Donasi di pojok kanan atas
        title_frame = ttk.Frame(main_frame)
        title_frame.pack(fill=tk.X, pady=(0, 20))

        # Title di kiri
        title_label = ttk.Label(title_frame, text="APLIKASI OTOMASI", 
                            font=('Arial', 20, 'bold'))
        title_label.pack(side=tk.LEFT)

        # Tombol Donasi di kanan atas
        donasi_frame = ttk.Frame(title_frame)
        donasi_frame.pack(side=tk.RIGHT, anchor=tk.NE)

        donasi_btn = tk.Button(
            donasi_frame,
            text="☕ Dukung Kami",
            font=('Arial', 10, 'bold'),
            fg='#FF6B00',
            bg='#FFF3E0',
            relief=tk.RAISED,
            bd=2,
            padx=12,
            pady=5,
            cursor='hand2',
            command=self.open_donasi
        )
        donasi_btn.pack()

        # Tooltip / subtext
        donasi_sub = tk.Label(
            donasi_frame,
            text="saweria.co/ancaswasetoy98",
            font=('Arial', 7),
            fg='#888888',
            cursor='hand2'
        )
        donasi_sub.pack()
        donasi_sub.bind('<Button-1>', lambda e: self.open_donasi())
        
        # Create paned window with better proportions
        paned = ttk.PanedWindow(main_frame, orient=tk.HORIZONTAL)
        paned.pack(fill=tk.BOTH, expand=True)
        
        # Left panel - lebih kecil (weight=1)
        left_frame = ttk.Frame(paned)
        paned.add(left_frame, weight=1)
        
        # Right panel - lebih besar (weight=5)
        right_frame = ttk.Frame(paned)
        paned.add(right_frame, weight=5)
        
        # === LEFT PANEL ===
        # File Section
        file_frame = ttk.LabelFrame(left_frame, text="File Excel", padding="12")
        file_frame.pack(fill=tk.X, pady=(0, 12))
        
        file_entry_frame = ttk.Frame(file_frame)
        file_entry_frame.pack(fill=tk.X)
        self.file_entry = ttk.Entry(file_entry_frame, textvariable=self.excel_file_path, font=('Arial', 10))
        self.file_entry.pack(side=tk.LEFT, fill=tk.X, expand=True, padx=(0, 8))
        ttk.Button(file_entry_frame, text="Browse", command=self.browse_excel).pack(side=tk.RIGHT)
        
        # Config Section
        config_frame = ttk.LabelFrame(left_frame, text="Konfigurasi", padding="12")
        config_frame.pack(fill=tk.X, pady=(0, 12))
        
        ttk.Label(config_frame, text="Simpan/Load Konfigurasi:").pack(anchor=tk.W)
        config_btn_frame = ttk.Frame(config_frame)
        config_btn_frame.pack(fill=tk.X, pady=5)
        ttk.Button(config_btn_frame, text="💾 Save Config", command=self.save_config).pack(side=tk.LEFT, padx=5)
        ttk.Button(config_btn_frame, text="📂 Load Config", command=self.load_config_dialog).pack(side=tk.LEFT, padx=5)
        
        # Excel Column Mapping - READ ONLY (Label instead of Entry)
        mapping_frame = ttk.LabelFrame(left_frame, text="Header Excel (Read Only)", padding="12")
        mapping_frame.pack(fill=tk.BOTH, expand=True, pady=(0, 12))
        
        # Canvas for mapping with scrollbars
        mapping_container = ttk.Frame(mapping_frame)
        mapping_container.pack(fill=tk.BOTH, expand=True)
        
        mapping_canvas = tk.Canvas(mapping_container, height=180)
        mapping_canvas.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        
        mapping_v_scroll = ttk.Scrollbar(mapping_container, orient=tk.VERTICAL, command=mapping_canvas.yview)
        mapping_v_scroll.pack(side=tk.RIGHT, fill=tk.Y)
        
        mapping_h_scroll = ttk.Scrollbar(mapping_container, orient=tk.HORIZONTAL, command=mapping_canvas.xview)
        mapping_h_scroll.pack(side=tk.BOTTOM, fill=tk.X)
        
        mapping_canvas.configure(yscrollcommand=mapping_v_scroll.set, xscrollcommand=mapping_h_scroll.set)
        
        self.mapping_container = ttk.Frame(mapping_canvas)
        mapping_canvas_window = mapping_canvas.create_window((0, 0), window=self.mapping_container, anchor=tk.NW)
        
        def configure_mapping_scroll(event):
            mapping_canvas.configure(scrollregion=mapping_canvas.bbox("all"))
        
        self.mapping_container.bind("<Configure>", configure_mapping_scroll)
        
        # Global Settings
        settings_frame = ttk.LabelFrame(left_frame, text="Pengaturan Global", padding="12")
        settings_frame.pack(fill=tk.X)
        
        # Start row
        start_row_frame = ttk.Frame(settings_frame)
        start_row_frame.pack(fill=tk.X, pady=5)
        ttk.Label(start_row_frame, text="Mulai dari Baris:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        self.start_row_entry = ttk.Entry(start_row_frame, width=12, font=('Arial', 10))
        self.start_row_entry.insert(0, "1")
        self.start_row_entry.pack(side=tk.LEFT)
        
        # Default delay
        delay_frame = ttk.Frame(settings_frame)
        delay_frame.pack(fill=tk.X, pady=5)
        ttk.Label(delay_frame, text="Delay Default (detik):", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        self.default_delay_entry = ttk.Entry(delay_frame, width=12, font=('Arial', 10))
        self.default_delay_entry.insert(0, "1")
        self.default_delay_entry.pack(side=tk.LEFT)
        
        # Trigger key setting
        trigger_frame = ttk.Frame(settings_frame)
        trigger_frame.pack(fill=tk.X, pady=5)
        ttk.Label(trigger_frame, text="Trigger Key Mulai:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        self.trigger_key_entry = ttk.Entry(trigger_frame, width=20, font=('Arial', 10))
        self.trigger_key_entry.insert(0, self.start_hotkey)
        self.trigger_key_entry.pack(side=tk.LEFT)
        ttk.Button(trigger_frame, text="Set", command=self.set_trigger_key, width=8).pack(side=tk.LEFT, padx=5)
        ttk.Label(trigger_frame, text="(contoh: ctrl+shift+s)", font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=5)
        
        # Start delay setting
        delay_start_frame = ttk.Frame(settings_frame)
        delay_start_frame.pack(fill=tk.X, pady=5)
        ttk.Label(delay_start_frame, text="Delay Mulai (detik):", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        self.start_delay_entry = ttk.Entry(delay_start_frame, width=12, font=('Arial', 10))
        self.start_delay_entry.insert(0, "5")
        self.start_delay_entry.pack(side=tk.LEFT)
        ttk.Label(delay_start_frame, text="(0 = langsung mulai)", font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=5)
       
        # Steps per trigger setting
        steps_per_trigger_frame = ttk.Frame(settings_frame)
        steps_per_trigger_frame.pack(fill=tk.X, pady=5)
        ttk.Label(steps_per_trigger_frame, text="Langkah per Trigger:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        self.steps_per_trigger_entry = ttk.Entry(steps_per_trigger_frame, width=12, font=('Arial', 10))
        self.steps_per_trigger_entry.insert(0, "2")
        self.steps_per_trigger_entry.pack(side=tk.LEFT)
        ttk.Label(steps_per_trigger_frame, text="(jumlah step yg dijalankan per trigger)", font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=5)
        
        # Custom Groups button
        custom_group_frame = ttk.Frame(settings_frame)
        custom_group_frame.pack(fill=tk.X, pady=5)
        ttk.Button(custom_group_frame, text="⚙️ Atur Custom Groups", 
                   command=self.show_custom_groups_dialog, width=20).pack(side=tk.LEFT, padx=5)
        ttk.Label(custom_group_frame, text="(atur kelompok langkah manual)", 
                  font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=5)

        # Button to add step
        ttk.Button(left_frame, text="➕ Tambah Langkah", command=self.show_add_step_dialog, 
                width=20).pack(pady=15)
        
        # Control buttons
        control_frame = ttk.Frame(left_frame)
        control_frame.pack(fill=tk.X, pady=10)

        self.start_btn = ttk.Button(control_frame, text="▶ Mulai Otomasi", 
                                command=self.start_automation, width=15)
        self.start_btn.pack(side=tk.LEFT, padx=5)

        self.step_mode_btn = ttk.Button(control_frame, text="🔑 Step Mode", 
                                    command=self.start_step_mode, width=10)
        self.step_mode_btn.pack(side=tk.LEFT, padx=5)

        self.stop_btn = ttk.Button(control_frame, text="⏹ Stop", 
                                command=self.stop_automation, state=tk.DISABLED, width=10)
        self.stop_btn.pack(side=tk.LEFT, padx=5)

        ttk.Button(control_frame, text="🗑 Clear Steps", 
                command=self.clear_steps).pack(side=tk.LEFT, padx=5)
        
        # Shortcut info
        shortcut_frame = ttk.Frame(left_frame)
        shortcut_frame.pack(fill=tk.X, pady=8)
        ttk.Label(shortcut_frame, text=f"Stop Shortcut: {self.stop_hotkey.upper()}", 
                font=('Arial', 10, 'bold'), foreground='red').pack()
        
        scroll_info_frame = ttk.Frame(left_frame)
        scroll_info_frame.pack(fill=tk.X, pady=2)
        ttk.Label(scroll_info_frame, text="Scroll: Mouse Wheel (V) | Shift+Wheel (H)", 
                font=('Arial', 8), foreground='gray').pack()
        
        # === RIGHT PANEL ===
        steps_frame = ttk.LabelFrame(right_frame, text="Daftar Langkah", padding="12")
        steps_frame.pack(fill=tk.BOTH, expand=True)
        
        # Listbox with scrollbars
        listbox_container = ttk.Frame(steps_frame)
        listbox_container.pack(fill=tk.BOTH, expand=True)
        
        # Vertical scrollbar
        listbox_v_scroll = ttk.Scrollbar(listbox_container, orient=tk.VERTICAL)
        listbox_v_scroll.pack(side=tk.RIGHT, fill=tk.Y)
        
        # Horizontal scrollbar
        listbox_h_scroll = ttk.Scrollbar(listbox_container, orient=tk.HORIZONTAL)
        listbox_h_scroll.pack(side=tk.BOTTOM, fill=tk.X)
        
        self.step_listbox = tk.Listbox(listbox_container, 
                                    yscrollcommand=listbox_v_scroll.set,
                                    xscrollcommand=listbox_h_scroll.set,
                                    font=('Courier', 10),
                                    selectmode=tk.EXTENDED,
                                    activestyle='dotbox')
        self.step_listbox.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)

        # Bind selection event untuk update status
        self.step_listbox.bind('<<ListboxSelect>>', self.on_step_selection)
        
        listbox_v_scroll.config(command=self.step_listbox.yview)
        listbox_h_scroll.config(command=self.step_listbox.xview)
        
        # Step controls
        step_controls_container = ttk.Frame(steps_frame)
        step_controls_container.pack(fill=tk.X, pady=8)

        # Canvas untuk scroll horizontal
        step_canvas = tk.Canvas(step_controls_container, height=50, highlightthickness=0)
        step_canvas.pack(side=tk.LEFT, fill=tk.X, expand=True)

        # Scrollbar horizontal
        step_scrollbar = tk.Scrollbar(step_controls_container, orient=tk.HORIZONTAL, 
                                    command=step_canvas.xview,
                                    width=18)
        step_scrollbar.pack(side=tk.BOTTOM, fill=tk.X)

        step_canvas.configure(xscrollcommand=step_scrollbar.set)

        # Frame untuk tombol
        step_buttons_frame = ttk.Frame(step_canvas)
        step_canvas.create_window((0, 0), window=step_buttons_frame, anchor=tk.NW)

        # Tombol-tombol
        buttons = [
            ("⬆ Up", self.move_step_up, 6),
            ("⬇ Down", self.move_step_down, 7),
            ("", None, 1),
            ("⬆ Up All", self.move_selected_up, 8),
            ("⬇ Down All", self.move_selected_down, 10),
            ("⤒ Top", self.move_selected_to_top, 7),
            ("⤓ Bottom", self.move_selected_to_bottom, 9),
            ("", None, 1),
            ("✏️ Edit", self.edit_step, 6),
            ("📋 Dup", self.duplicate_selected_steps, 7),
            ("❌ Del", self.delete_selected_steps, 6),
            ("", None, 1),
            ("🔲 Select", self.select_all_steps, 8),
            ("🔳 Deselect", self.deselect_all_steps, 9),
            ("✅ Enable", self.enable_all_steps, 8),
        ]

        for text, command, width in buttons:
            if text == "" and command is None:
                ttk.Frame(step_buttons_frame, width=10).pack(side=tk.LEFT)
            else:
                btn = ttk.Button(step_buttons_frame, text=text, command=command, width=width)
                btn.pack(side=tk.LEFT, padx=2, pady=2)

        # Update scroll region
        def update_step_scroll_region(event):
            step_canvas.configure(scrollregion=step_canvas.bbox("all"))

        step_buttons_frame.bind("<Configure>", update_step_scroll_region)

        # Scroll horizontal dengan mouse wheel + Shift
        def on_step_scroll(event):
            if event.state & 0x0001:
                step_canvas.xview_scroll(int(-1*(event.delta/120)), "units")

        step_canvas.bind_all("<MouseWheel>", on_step_scroll)
        
        # Status
        status_frame = ttk.Frame(main_frame)
        status_frame.pack(fill=tk.X, pady=(12, 0))
        
        self.status_var = tk.StringVar(value="Siap menjalankan otomasi...")
        ttk.Label(status_frame, textvariable=self.status_var, relief=tk.SUNKEN, 
                anchor=tk.W, font=('Arial', 10)).pack(fill=tk.X, ipady=5)

       
        
        # Progress
        self.progress_var = tk.DoubleVar()
        self.progress_bar = ttk.Progressbar(main_frame, variable=self.progress_var, maximum=100, length=100)
        self.progress_bar.pack(fill=tk.X, pady=8)
        
        # Log
        log_frame = ttk.LabelFrame(main_frame, text="Log", padding="8")
        log_frame.pack(fill=tk.BOTH, expand=True, pady=(0, 5))
        
        self.log_text = scrolledtext.ScrolledText(log_frame, height=12, font=('Courier', 9))
        self.log_text.pack(fill=tk.BOTH, expand=True)
        
        # Configure log tags
        self.log_text.tag_config('success', foreground='green')
        self.log_text.tag_config('error', foreground='red')
        self.log_text.tag_config('info', foreground='blue')
        self.log_text.tag_config('warning', foreground='orange')
        self.log_text.tag_config('step', foreground='purple')
        self.log_text.tag_config('calib', foreground='darkorange')
        self.log_text.tag_config('clean', foreground='brown')
        
        # Force update layout
        self.root.update_idletasks()
        
        # Set sash position
        self.root.after(100, lambda: paned.sashpos(0, 400))
        
        self.log("Aplikasi Otomasi siap digunakan!", 'info')
        self.log(f"Tekan {self.stop_hotkey.upper()} untuk menghentikan proses", 'warning')
        self.log("Scroll: Mouse Wheel (Vertikal) | Shift+Wheel (Horizontal)", 'info')
        self.log("Tambahkan langkah-langkah otomasi dengan tombol 'Tambah Langkah'", 'info')


    
    def log(self, message, tag=None):
        timestamp = time.strftime("%H:%M:%S")
        log_message = f"[{timestamp}] {message}\n"
        self.log_text.insert(tk.END, log_message, tag)
        self.log_text.see(tk.END)
        self.root.update_idletasks()
    
    def update_status(self, message):
        self.status_var.set(message)
        self.root.update_idletasks()
    
    def update_progress(self, current, total):
        if total > 0:
            self.progress_var.set((current / total) * 100)
            self.root.update_idletasks()
    
    def browse_excel(self):
        file_path = filedialog.askopenfilename(
            title="Pilih File Excel",
            filetypes=[("Excel files", "*.xlsx *.xls"), ("All files", "*.*")]
        )
        if file_path:
            self.excel_file_path.set(file_path)
            try:
                df = pd.read_excel(file_path)
                self.excel_columns = df.columns.tolist()
                self.log(f"File loaded: {len(df)} baris, {len(self.excel_columns)} kolom", 'success')
                self.update_mapping_ui()
            except Exception as e:
                self.log(f"Error: {str(e)}", 'error')
    
    def update_mapping_ui(self):
        # Bersihkan mapping container
        for widget in self.mapping_container.winfo_children():
            widget.destroy()
        
        # Reset column_mapping
        self.column_mapping = {}
        
        if not self.excel_columns:
            ttk.Label(self.mapping_container, text="Tidak ada kolom ditemukan", 
                    font=('Arial', 10)).pack(anchor=tk.W, pady=8)
            return
        
        # Header dengan label READ ONLY
        ttk.Label(self.mapping_container, text="📋 Header Excel (Read Only):", 
                font=('Arial', 10, 'bold')).pack(anchor=tk.W, pady=(0, 5))
        
        ttk.Label(self.mapping_container, text="Data akan digunakan sesuai urutan kolom di Excel", 
                font=('Arial', 8), foreground='gray').pack(anchor=tk.W, pady=(0, 8))
        
        # Tampilkan kolom sebagai Label (read-only)
        for col in self.excel_columns:
            col_frame = ttk.Frame(self.mapping_container)
            col_frame.pack(fill=tk.X, pady=2)
            
            # Gunakan Label bukan Entry
            ttk.Label(col_frame, text=f"📊 {col}", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
            
            # Indikator read-only
            ttk.Label(col_frame, text="🔒 read-only", 
                     font=('Arial', 8), foreground='gray').pack(side=tk.LEFT)
            
            # Simpan kolom di mapping (tanpa entry)
            self.column_mapping[col] = None
    
    def clean_numbers_only(self, text):
        """Membersihkan teks, hanya menyisakan angka"""
        if text is None:
            return ""
        return re.sub(r'[^0-9]', '', str(text))
    
    def is_nan_value(self, value):
        """Cek apakah nilai adalah NaN atau kosong"""
        if value is None:
            return True
        if isinstance(value, float) and math.isnan(value):
            return True
        if isinstance(value, str) and value.lower() in ['nan', 'none', 'null', '']:
            return True
        if isinstance(value, (int, float)) and str(value).lower() in ['nan', 'none']:
            return True
        return False
    
    def capture_position(self, x_entry, y_entry):
        # Simpan state window sebelum minimize
        self.root.update_idletasks()
        
        self.root.iconify()
        self.root.update()
        time.sleep(0.8)  # beri waktu minimize selesai
        
        self.log("📷 Mode capture posisi aktif.", 'calib')
        self.log("Klik kiri mouse untuk capture posisi", 'calib')
        self.log("Klik kanan untuk batal", 'calib')
        
        region = None
        try:
            self.region_selector.mode = "point"
            region = self.region_selector.start_selection()
        except Exception as e:
            self.log(f"❌ Error capture: {str(e)}", 'error')
        finally:
            # Selalu kembalikan window apapun yang terjadi
            self.region_selector.mode = "region"
            self._restore_window()
        
        if region:
            x, y, width, height = region
            center_x = x + width//2
            center_y = y + height//2
            
            x_entry.delete(0, tk.END)
            x_entry.insert(0, str(center_x))
            y_entry.delete(0, tk.END)
            y_entry.insert(0, str(center_y))
            
            self.log(f"✅ Position captured: ({center_x}, {center_y})", 'success')
        else:
            self.log("❌ Position capture dibatalkan", 'warning')

    def _restore_window(self):
        """Restore window dengan paksa ke foreground"""
        try:
            self.root.deiconify()
            self.root.update_idletasks()
            self.root.update()
            
            # Paksa lift & focus (beberapa kali untuk memastikan)
            self.root.lift()
            self.root.attributes('-topmost', True)
            self.root.after(200, lambda: self.root.attributes('-topmost', False))
            
            # Paksa focus ke window
            self.root.focus_force()
            self.root.update()
            
            # Jika masih minimize, coba state normal
            if self.root.state() == 'iconic':
                self.root.state('normal')
                self.root.update()
            
            self.log("🔙 Window dikembalikan", 'info')
        except Exception as e:
            self.log(f"⚠️ Gagal restore window: {str(e)}", 'warning')
    
    def show_add_step_dialog(self):
        dialog = tk.Toplevel(self.root)
        dialog.title("Tambah Langkah")
        dialog.geometry("750x850")
        dialog.transient(self.root)
        dialog.grab_set()
        
        dialog_container = ttk.Frame(dialog)
        dialog_container.pack(fill=tk.BOTH, expand=True)
        
        dialog_canvas = tk.Canvas(dialog_container)
        dialog_canvas.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        
        dialog_v_scroll = ttk.Scrollbar(dialog_container, orient=tk.VERTICAL, command=dialog_canvas.yview)
        dialog_v_scroll.pack(side=tk.RIGHT, fill=tk.Y)
        
        dialog_h_scroll = ttk.Scrollbar(dialog_container, orient=tk.HORIZONTAL, command=dialog_canvas.xview)
        dialog_h_scroll.pack(side=tk.BOTTOM, fill=tk.X)
        
        dialog_canvas.configure(yscrollcommand=dialog_v_scroll.set, xscrollcommand=dialog_h_scroll.set)
        
        dialog_frame = ttk.Frame(dialog_canvas, padding="20")
        dialog_canvas_window = dialog_canvas.create_window((0, 0), window=dialog_frame, anchor=tk.NW)
        
        def configure_dialog_scroll(event):
            dialog_canvas.configure(scrollregion=dialog_canvas.bbox("all"))
        
        dialog_frame.bind("<Configure>", configure_dialog_scroll)
        
        # Step type
        ttk.Label(dialog_frame, text="Jenis Langkah:", font=('Arial', 11, 'bold')).pack(pady=5)
        step_type_var = tk.StringVar(value="click")
        type_frame = ttk.Frame(dialog_frame)
        type_frame.pack()
        
        types = [
            ("Klik", "click"),
            ("Ketik", "type"),
            ("Tunggu", "wait"),
            ("Key Combo", "key"),
            ("Scroll", "scroll"),
            ("Drag & Drop", "drag")
        ]
        
        for text, value in types:
            ttk.Radiobutton(type_frame, text=text, value=value, 
                          variable=step_type_var).pack(side=tk.LEFT, padx=8)
        
        # Name
        name_frame = ttk.Frame(dialog_frame)
        name_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(name_frame, text="Nama Langkah:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        name_entry = ttk.Entry(name_frame, width=35, font=('Arial', 10))
        name_entry.pack(side=tk.LEFT, fill=tk.X, expand=True)
        
        # Position
        pos_frame = ttk.Frame(dialog_frame)
        pos_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(pos_frame, text="Posisi (x,y):", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        pos_x_entry = ttk.Entry(pos_frame, width=10, font=('Arial', 10))
        pos_x_entry.pack(side=tk.LEFT, padx=5)
        ttk.Label(pos_frame, text=",").pack(side=tk.LEFT)
        pos_y_entry = ttk.Entry(pos_frame, width=10, font=('Arial', 10))
        pos_y_entry.pack(side=tk.LEFT, padx=5)
        ttk.Button(pos_frame, text="📷 klik tujuan", 
                  command=lambda: self.capture_position(pos_x_entry, pos_y_entry)).pack(side=tk.LEFT, padx=10)
        
        # Text
        text_frame = ttk.Frame(dialog_frame)
        text_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(text_frame, text="Text:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        text_entry = ttk.Entry(text_frame, width=45, font=('Arial', 10))
        text_entry.pack(side=tk.LEFT, fill=tk.X, expand=True)
        ttk.Label(text_frame, text="(Gunakan {col} untuk header Excel)", 
                 font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=8)
        
        # Mode Ketik
        mode_frame = ttk.Frame(dialog_frame)
        mode_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(mode_frame, text="Mode Ketik:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        
        type_mode_var = tk.StringVar(value="all")
        
        all_radio = ttk.Radiobutton(mode_frame, text="Semua Karakter", 
                                    value="all", variable=type_mode_var)
        all_radio.pack(side=tk.LEFT, padx=5)
        
        numbers_radio = ttk.Radiobutton(mode_frame, text="Hanya Angka (bersihkan)", 
                                        value="numbers_only", variable=type_mode_var)
        numbers_radio.pack(side=tk.LEFT, padx=5)
        
        ttk.Label(mode_frame, text="(Angka: hapus tanda petik ' & karakter non-angka)", 
                 font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=8)
        
        # Key combination
        key_frame = ttk.Frame(dialog_frame)
        key_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(key_frame, text="Key Combo:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        key_entry = ttk.Entry(key_frame, width=35, font=('Arial', 10))
        key_entry.pack(side=tk.LEFT, fill=tk.X, expand=True)
        ttk.Label(key_frame, text="(ctrla, delete, enter, ctrl+c)", 
                 font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=8)
        
        # Delay
        delay_frame = ttk.Frame(dialog_frame)
        delay_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(delay_frame, text="Delay (detik):", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        delay_entry = ttk.Entry(delay_frame, width=12, font=('Arial', 10))
        delay_entry.insert(0, self.default_delay_entry.get())
        delay_entry.pack(side=tk.LEFT)
        
        # Scroll amount
        scroll_frame = ttk.Frame(dialog_frame)
        scroll_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(scroll_frame, text="Scroll Amount:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        scroll_entry = ttk.Entry(scroll_frame, width=12, font=('Arial', 10))
        scroll_entry.insert(0, "0")
        scroll_entry.pack(side=tk.LEFT)
        ttk.Label(scroll_frame, text="(positif=down, negatif=up)", font=('Arial', 8), foreground='gray').pack(side=tk.LEFT, padx=8)
        
        # Drag to position
        drag_frame = ttk.Frame(dialog_frame)
        drag_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(drag_frame, text="Drag Ke (x,y):", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        drag_x_entry = ttk.Entry(drag_frame, width=10, font=('Arial', 10))
        drag_x_entry.pack(side=tk.LEFT, padx=5)
        ttk.Label(drag_frame, text=",").pack(side=tk.LEFT)
        drag_y_entry = ttk.Entry(drag_frame, width=10, font=('Arial', 10))
        drag_y_entry.pack(side=tk.LEFT, padx=5)
        ttk.Button(drag_frame, text="📷 klik tujuan", 
                  command=lambda: self.capture_position(drag_x_entry, drag_y_entry)).pack(side=tk.LEFT, padx=10)
        
        # Buttons
        btn_frame = ttk.Frame(dialog_frame)
        btn_frame.pack(pady=15)
        
        def add_step():
            try:
                step_type = step_type_var.get()
                name = name_entry.get() or f"Step {len(self.steps)+1}"
                position = None
                if pos_x_entry.get() and pos_y_entry.get():
                    position = (int(pos_x_entry.get()), int(pos_y_entry.get()))
                
                drag_to = None
                if drag_x_entry.get() and drag_y_entry.get():
                    drag_to = (int(drag_x_entry.get()), int(drag_y_entry.get()))
                
                step = Step(
                    step_type=step_type,
                    name=name,
                    position=position,
                    delay=float(delay_entry.get() or "0.5"),
                    text=text_entry.get(),
                    key_combination=key_entry.get().lower(),
                    scroll_amount=int(scroll_entry.get() or "0"),
                    drag_to=drag_to,
                    type_mode=type_mode_var.get()
                )
                
                self.steps.append(step)
                self.update_step_list()
                mode_text = "Hanya Angka" if type_mode_var.get() == "numbers_only" else "Semua Karakter"
                self.log(f"✅ Langkah ditambahkan: {step_type} - {name} (Mode: {mode_text})", 'step')
                dialog.destroy()
                
            except Exception as e:
                messagebox.showerror("Error", f"Error: {str(e)}")
        
        ttk.Button(btn_frame, text="Tambah", command=add_step, width=15).pack(side=tk.LEFT, padx=10)
        ttk.Button(btn_frame, text="Batal", command=dialog.destroy, width=15).pack(side=tk.LEFT, padx=10)
    
    def update_step_list(self):
        self.step_listbox.delete(0, tk.END)
        for i, step in enumerate(self.steps, 1):
            status = "✅" if step.enabled else "❌"
            pos_text = f" ({step.position[0]},{step.position[1]})" if step.position else ""
            text_info = f" - {step.text}" if step.text else ""
            key_info = f" - {step.key_combination}" if step.key_combination else ""
            scroll_info = f" - scroll:{step.scroll_amount}" if step.scroll_amount != 0 else ""
            drag_info = f" -> ({step.drag_to[0]},{step.drag_to[1]})" if step.drag_to else ""
            mode_info = " [Angka]" if step.type_mode == "numbers_only" else ""
            
            display = f"{i:2}. {status} {step.step_type.upper():6} {step.name}{pos_text}{text_info}{key_info}{scroll_info}{drag_info}{mode_info} (delay:{step.delay}s)"
            self.step_listbox.insert(tk.END, display)
        
        self.step_listbox.xview_moveto(1.0)
    
    def move_step_up(self):
        selected = self.step_listbox.curselection()
        if selected and selected[0] > 0:
            idx = selected[0]
            self.steps[idx], self.steps[idx-1] = self.steps[idx-1], self.steps[idx]
            self.update_step_list()
            self.step_listbox.selection_set(idx-1)
    
    def move_step_down(self):
        selected = self.step_listbox.curselection()
        if selected and selected[0] < len(self.steps) - 1:
            idx = selected[0]
            self.steps[idx], self.steps[idx+1] = self.steps[idx+1], self.steps[idx]
            self.update_step_list()
            self.step_listbox.selection_set(idx+1)
    
    def delete_step(self):
        selected = self.step_listbox.curselection()
        if selected:
            idx = selected[0]
            self.steps.pop(idx)
            self.update_step_list()
            self.log(f"Langkah {idx+1} dihapus", 'warning')
    
    def enable_all_steps(self):
        for step in self.steps:
            step.enabled = True
        self.update_step_list()
        self.log("Semua langkah diaktifkan", 'info')
    
    def edit_step(self):
        selected = self.step_listbox.curselection()
        if not selected:
            return
        
        idx = selected[0]
        step = self.steps[idx]
        
        dialog = tk.Toplevel(self.root)
        dialog.title(f"Edit Langkah {idx+1}")
        dialog.geometry("650x750")
        dialog.transient(self.root)
        dialog.grab_set()
        
        dialog_container = ttk.Frame(dialog)
        dialog_container.pack(fill=tk.BOTH, expand=True)
        
        dialog_canvas = tk.Canvas(dialog_container)
        dialog_canvas.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        
        dialog_v_scroll = ttk.Scrollbar(dialog_container, orient=tk.VERTICAL, command=dialog_canvas.yview)
        dialog_v_scroll.pack(side=tk.RIGHT, fill=tk.Y)
        
        dialog_h_scroll = ttk.Scrollbar(dialog_container, orient=tk.HORIZONTAL, command=dialog_canvas.xview)
        dialog_h_scroll.pack(side=tk.BOTTOM, fill=tk.X)
        
        dialog_canvas.configure(yscrollcommand=dialog_v_scroll.set, xscrollcommand=dialog_h_scroll.set)
        
        dialog_frame = ttk.Frame(dialog_canvas, padding="20")
        dialog_canvas_window = dialog_canvas.create_window((0, 0), window=dialog_frame, anchor=tk.NW)
        
        def configure_dialog_scroll(event):
            dialog_canvas.configure(scrollregion=dialog_canvas.bbox("all"))
        
        dialog_frame.bind("<Configure>", configure_dialog_scroll)
        
        # Name
        ttk.Label(dialog_frame, text="Nama:", font=('Arial', 10)).pack(pady=5)
        name_entry = ttk.Entry(dialog_frame, width=45, font=('Arial', 10))
        name_entry.insert(0, step.name)
        name_entry.pack()
        
        # Position
        pos_frame = ttk.Frame(dialog_frame)
        pos_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(pos_frame, text="Position (x,y):", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        pos_x_entry = ttk.Entry(pos_frame, width=10, font=('Arial', 10))
        pos_x_entry.insert(0, str(step.position[0]) if step.position else "")
        pos_x_entry.pack(side=tk.LEFT, padx=5)
        ttk.Label(pos_frame, text=",").pack(side=tk.LEFT)
        pos_y_entry = ttk.Entry(pos_frame, width=10, font=('Arial', 10))
        pos_y_entry.insert(0, str(step.position[1]) if step.position else "")
        pos_y_entry.pack(side=tk.LEFT, padx=5)
        ttk.Button(pos_frame, text="📷 klik tujuan", 
                command=lambda: self.capture_position(pos_x_entry, pos_y_entry)).pack(side=tk.LEFT, padx=5)
        
        # Text
        text_frame = ttk.Frame(dialog_frame)
        text_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(text_frame, text="Text:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        text_entry = ttk.Entry(text_frame, width=45, font=('Arial', 10))
        text_entry.insert(0, step.text)
        text_entry.pack(side=tk.LEFT, fill=tk.X, expand=True)
        
        # Mode Ketik (Edit)
        mode_frame = ttk.Frame(dialog_frame)
        mode_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(mode_frame, text="Mode Ketik:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        
        type_mode_var = tk.StringVar(value=step.type_mode)
        
        all_radio = ttk.Radiobutton(mode_frame, text="Semua Karakter", 
                                    value="all", variable=type_mode_var)
        all_radio.pack(side=tk.LEFT, padx=5)
        
        numbers_radio = ttk.Radiobutton(mode_frame, text="Hanya Angka (bersihkan)", 
                                        value="numbers_only", variable=type_mode_var)
        numbers_radio.pack(side=tk.LEFT, padx=5)
        
        # Key combo
        key_frame = ttk.Frame(dialog_frame)
        key_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(key_frame, text="Key Combo:", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        key_entry = ttk.Entry(key_frame, width=35, font=('Arial', 10))
        key_entry.insert(0, step.key_combination)
        key_entry.pack(side=tk.LEFT, fill=tk.X, expand=True)
        
        # Delay
        delay_frame = ttk.Frame(dialog_frame)
        delay_frame.pack(fill=tk.X, pady=8, padx=20)
        ttk.Label(delay_frame, text="Delay (detik):", font=('Arial', 10)).pack(side=tk.LEFT, padx=(0, 10))
        delay_entry = ttk.Entry(delay_frame, width=12, font=('Arial', 10))
        delay_entry.insert(0, str(step.delay))
        delay_entry.pack(side=tk.LEFT)
        
        # Enabled
        enabled_var = tk.BooleanVar(value=step.enabled)
        enabled_check = tk.Checkbutton(dialog_frame, text="Aktif", variable=enabled_var, 
                                    font=('Arial', 10), bg='white')
        enabled_check.pack(pady=8)
        
        def save_edit():
            try:
                step.name = name_entry.get()
                if pos_x_entry.get() and pos_y_entry.get():
                    step.position = (int(pos_x_entry.get()), int(pos_y_entry.get()))
                else:
                    step.position = None
                step.text = text_entry.get()
                step.type_mode = type_mode_var.get()
                step.key_combination = key_entry.get().lower()
                step.delay = float(delay_entry.get())
                step.enabled = enabled_var.get()
                self.update_step_list()
                self.log(f"Langkah {idx+1} diupdate", 'info')
                dialog.destroy()
            except Exception as e:
                messagebox.showerror("Error", str(e))
        
        ttk.Button(dialog_frame, text="Save", command=save_edit, width=15).pack(pady=15)
    
    def clear_steps(self):
        if self.steps:
            if messagebox.askyesno("Konfirmasi", "Hapus semua langkah?"):
                self.steps = []
                self.update_step_list()
                self.log("Semua langkah dihapus", 'warning')
    
    def is_nan_value(self, value):
        """Cek apakah nilai adalah NaN atau kosong"""
        if value is None:
            return True
        if isinstance(value, float) and math.isnan(value):
            return True
        if isinstance(value, str) and value.lower() in ['nan', 'none', 'null', '']:
            return True
        if isinstance(value, (int, float)) and str(value).lower() in ['nan', 'none']:
            return True
        return False
    
    def execute_step(self, step, row_data, index, total):
        try:
            self.log(f"  ▶ {step.name} (Delay: {step.delay}s)", 'step')
            
            # Process text with column data
            text = step.text
            if text and '{' in text and '}' in text:
                for col in self.excel_columns:
                    placeholder = f"{{{col}}}"
                    if placeholder in text:
                        # Ambil nilai dari Excel
                        value = row_data.get(col, '')
                        
                        # ===== CEK APAKAH NAN =====
                        if self.is_nan_value(value):
                            self.log(f"    ⚠️ Data kosong (NaN) untuk kolom {col}, skip pengetikan", 'warning')
                            # Skip langkah ini dan lanjut ke langkah berikutnya
                            return True
                        
                        # Konversi ke string untuk diproses
                        value = str(value)
                        
                        # ===== TERAPKAN MODE KETIK =====
                        if step.type_mode == "numbers_only":
                            # Hanya angka - bersihkan dari tanda petik dan karakter
                            original_value = value
                            value = re.sub(r'[^0-9]', '', value)
                            if original_value != value and value:
                                self.log(f"    Clean: '{original_value}' → '{value}' (Hanya Angka)", 'clean')
                            elif original_value != value and not value:
                                self.log(f"    ⚠️ Data '{original_value}' dibersihkan menjadi kosong, skip pengetikan", 'warning')
                                return True
                        
                        text = text.replace(placeholder, value)
            
            # Execute based on type
            if step.step_type == "click" and step.position:
                pyautogui.click(step.position[0], step.position[1])
                self.log(f"    Klik di ({step.position[0]}, {step.position[1]})", 'info')
                
            elif step.step_type == "type" and text:
                # ===== CEK APAKAH TEXT KOSONG =====
                if not text or text.strip() == '':
                    self.log(f"    ⚠️ Text kosong, skip pengetikan", 'warning')
                    return True
                
                # ===== TERAPKAN MODE KETIK UNTUK TEXT =====
                final_text = text
                if step.type_mode == "numbers_only":
                    original_text = final_text
                    final_text = re.sub(r'[^0-9]', '', final_text)
                    if original_text != final_text and final_text:
                        self.log(f"    Clean text: '{original_text}' → '{final_text}' (Hanya Angka)", 'clean')
                    elif original_text != final_text and not final_text:
                        self.log(f"    ⚠️ Text '{original_text}' dibersihkan menjadi kosong, skip pengetikan", 'warning')
                        return True
                
                if final_text == "{enter}":
                    pyautogui.press('enter')
                elif final_text == "{tab}":
                    pyautogui.press('tab')
                else:
                    pyautogui.write(final_text, interval=0.02)
                self.log(f"    Mengetik: {final_text}", 'info')
                
            elif step.step_type == "wait":
                time.sleep(step.delay)
                self.log(f"    Menunggu {step.delay}s", 'info')
                
            elif step.step_type == "key" and step.key_combination:
                key_combo = step.key_combination.lower().strip()
                combos = [c.strip() for c in key_combo.split(',')]
                
                # Mapping nama key khusus
                key_map = {
                    'down': 'down', 'up': 'up', 'left': 'left', 'right': 'right',
                    'enter': 'enter', 'return': 'enter', 'esc': 'esc', 'escape': 'esc',
                    'tab': 'tab', 'space': 'space', 'backspace': 'backspace',
                    'delete': 'delete', 'del': 'delete', 'home': 'home', 'end': 'end',
                    'pageup': 'pageup', 'pagedown': 'pagedown',
                    'f1': 'f1', 'f2': 'f2', 'f3': 'f3', 'f4': 'f4', 'f5': 'f5',
                    'f6': 'f6', 'f7': 'f7', 'f8': 'f8', 'f9': 'f9', 'f10': 'f10',
                    'f11': 'f11', 'f12': 'f12',
                }
                
                for combo in combos:
                    if not combo:
                        continue
                    
                    # Parse kombinasi: ctrl+shift+down → ['ctrl', 'shift', 'down']
                    parts = [p.strip() for p in combo.split('+') if p.strip()]
                    
                    # Normalisasi nama key
                    normalized = []
                    for p in parts:
                        if p in ('ctrl', 'control'):
                            normalized.append('ctrl')
                        elif p in ('alt',):
                            normalized.append('alt')
                        elif p in ('shift',):
                            normalized.append('shift')
                        elif p in ('win', 'windows', 'cmd', 'super'):
                            normalized.append('win')
                        else:
                            # Cek mapping, kalau tidak ada pakai apa adanya
                            normalized.append(key_map.get(p, p))
                    
                    if len(normalized) == 1:
                        # Single key press
                        pyautogui.press(normalized[0])
                    else:
                        # Multi-key hotkey (misal: ctrl+shift+down)
                        pyautogui.hotkey(*normalized)
                    
                    time.sleep(0.1)
                
                self.log(f"    Key Combo: {step.key_combination}", 'info')
                
            elif step.step_type == "scroll":
                pyautogui.scroll(step.scroll_amount)
                direction = "down" if step.scroll_amount > 0 else "up"
                self.log(f"    Scroll {direction} {abs(step.scroll_amount)}", 'info')
                
            elif step.step_type == "drag" and step.position and step.drag_to:
                x1, y1 = step.position
                x2, y2 = step.drag_to
                
                # 1. Pindah ke titik awal
                pyautogui.moveTo(x1, y1)
                time.sleep(0.5)  # beri waktu OS & aplikasi siap
                
                # 2. Tekan tombol kiri (mulai drag)
                pyautogui.mouseDown(button='left')
                time.sleep(0.3)  # jeda penting! agar drag terdeteksi
                
                # 3. Gerakkan perlahan ke tujuan (multi-step agar terdeteksi)
                steps = 5  # jumlah langkah interpolasi
                for i in range(1, steps + 1):
                    ix = x1 + (x2 - x1) * i / steps
                    iy = y1 + (y2 - y1) * i / steps
                    pyautogui.moveTo(int(ix), int(iy))
                    time.sleep(0.02)  # ~0.5 detik total durasi drag
                
                # 4. Tahan sebentar di tujuan sebelum lepas
                time.sleep(0.3)
                
                # 5. Lepas tombol
                pyautogui.mouseUp(button='left')
                
                self.log(f"    Drag from ({x1},{y1}) to ({x2},{y2})", 'info')
            
            time.sleep(step.delay)
            return True
            
        except Exception as e:
            self.log(f"    ✗ Error: {str(e)}", 'error')
            return False
    
    def load_data_from_excel(self):
        try:
            df = pd.read_excel(self.excel_file_path.get())
            data_list = []
            for idx, row in df.iterrows():
                row_data = {}
                for col in self.excel_columns:
                    row_data[col] = row.get(col, '')
                data_list.append(row_data)
            return data_list
        except Exception as e:
            self.log(f"Error loading Excel: {str(e)}", 'error')
            return None
    
    def run_automation_thread(self):
        try:
            data_list = self.load_data_from_excel()
            if not data_list:
                return
            
            start_row = int(self.start_row_entry.get()) - 1
            total = len(data_list) - start_row
            if total <= 0:
                self.log("Tidak ada data untuk diproses!", 'error')
                return
            
            self.log(f"\nMemulai otomasi dengan {total} data...", 'info')
            self.log(f"Total langkah: {len(self.steps)}", 'info')
            
            success_count = 0
            fail_count = 0
            
            for i, row_data in enumerate(data_list[start_row:], start=1):
                if not self.is_running:
                    self.log("\nOtomasi dihentikan oleh user.", 'warning')
                    break
                
                self.update_status(f"Memproses data {i}/{total}")
                self.update_progress(i, total)
                
                self.log(f"\n=== Data {i}/{total} ===", 'info')
                for col, value in row_data.items():
                    if value and not self.is_nan_value(value):
                        self.log(f"  {col}: {value}", 'info')
                
                all_success = True
                for step_idx, step in enumerate(self.steps, 1):
                    if not self.is_running:
                        break
                    if not step.enabled:
                        continue
                    
                    self.log(f"  Langkah {step_idx}/{len(self.steps)}:", 'step')
                    if not self.execute_step(step, row_data, i, total):
                        all_success = False
                        break
                
                if all_success:
                    success_count += 1
                    self.results.append({'Row': i, 'Status': 'Success', **row_data})
                else:
                    fail_count += 1
                    self.results.append({'Row': i, 'Status': 'Failed', **row_data})
            
            self.update_progress(total, total)
            self.update_status("Otomasi selesai!")
            
            self.log("\n" + "="*50, 'info')
            self.log("OTOMASI SELESAI!", 'success')
            self.log(f"✓ Berhasil: {success_count}", 'success')
            self.log(f"✗ Gagal: {fail_count}", 'error')
            self.log(f"Total: {total}", 'info')
            
            # Pop up dihilangkan - hanya tampilkan di status
            self.update_status(f"Selesai! Berhasil: {success_count}, Gagal: {fail_count}")
            
        except Exception as e:
            self.log(f"Error fatal: {str(e)}", 'error')
        finally:
            self.is_running = False
            self.start_btn.config(state=tk.NORMAL)
            self.stop_btn.config(state=tk.DISABLED)
            self.update_status("Siap menjalankan otomasi...")
    
    def start_automation(self):
        if self.is_running:
            return
        
        if not self.excel_file_path.get():
            messagebox.showerror("Error", "Silakan pilih file Excel!")
            return
        
        if not self.steps:
            messagebox.showerror("Error", "Tambahkan langkah-langkah otomasi terlebih dahulu!")
            return
        
        # Ambil nilai delay dari entry
        try:
            self.start_delay = int(self.start_delay_entry.get())
            if self.start_delay < 0:
                self.start_delay = 0
        except:
            self.start_delay = 5
            self.start_delay_entry.delete(0, tk.END)
            self.start_delay_entry.insert(0, "5")
        
        self.is_running = True
        self.start_btn.config(state=tk.DISABLED)
        self.stop_btn.config(state=tk.NORMAL)
        self.progress_var.set(0)
        self.results = []
        
        if self.start_delay > 0:
            self.log(f"⏳ Otomasi akan dimulai dalam {self.start_delay} detik...", 'warning')
            self.update_status(f"Memulai dalam {self.start_delay} detik...")
        else:
            self.log("▶️ Otomasi dimulai langsung!", 'success')
            self.update_status("Menjalankan otomasi...")
        
        # Jalankan countdown di thread terpisah
        thread = threading.Thread(target=self.start_with_countdown)
        thread.daemon = True
        thread.start()
    
    def start_with_countdown(self):
        """Mulai otomasi dengan countdown sesuai delay yang diatur"""
        try:
            # Gunakan self.start_delay yang sudah diambil di start_automation
            delay = self.start_delay
            
            if delay > 0:
                for i in range(delay, 0, -1):
                    if not self.is_running:
                        self.log("⏹ Otomasi dibatalkan sebelum dimulai.", 'warning')
                        self.stop_btn.config(state=tk.DISABLED)
                        self.start_btn.config(state=tk.NORMAL)
                        self.update_status("Siap menjalankan otomasi...")
                        return
                    self.update_status(f"Memulai dalam {i} detik...")
                    self.log(f"⏳ {i}...", 'info')
                    time.sleep(1)
            
            if self.is_running:
                self.log("▶️ Otomasi dimulai!", 'success')
                self.update_status("Menjalankan otomasi...")
                self.run_automation_thread()
            else:
                self.log("⏹ Otomasi dibatalkan.", 'warning')
                self.stop_btn.config(state=tk.DISABLED)
                self.start_btn.config(state=tk.NORMAL)
                self.update_status("Siap menjalankan otomasi...")
        except Exception as e:
            self.log(f"Error: {str(e)}", 'error')
            self.is_running = False
            self.start_btn.config(state=tk.NORMAL)
            self.stop_btn.config(state=tk.DISABLED)
            self.update_status("Siap menjalankan otomasi...")
    
    # ==================== STEP-BY-STEP MODE (PER STEP) ====================
    
    def start_step_mode(self):
        """Memulai mode step-by-step (setiap STEP dijalankan dengan trigger key)"""
        if self.is_running or self.is_step_mode_running:
            return
        
        if not self.excel_file_path.get():
            messagebox.showerror("Error", "Silakan pilih file Excel!")
            return
        
        if not self.steps:
            messagebox.showerror("Error", "Tambahkan langkah-langkah otomasi terlebih dahulu!")
            return
        
        # Ambil data dari Excel
        self.step_mode_data = self.load_data_from_excel()
        if not self.step_mode_data:
            return
        
        start_row = int(self.start_row_entry.get()) - 1
        self.step_mode_data = self.step_mode_data[start_row:]
        self.step_mode_total_data = len(self.step_mode_data)
        
        if self.step_mode_total_data <= 0:
            self.log("Tidak ada data untuk diproses!", 'error')
            return
        
        # Reset state
        self.is_step_mode_running = True
        self.current_data_index = 0
        self.current_step_index = 0
        self.step_mode_results = []
        
        # Update UI
        self.start_btn.config(state=tk.DISABLED)
        self.step_mode_btn.config(state=tk.DISABLED)
        self.stop_btn.config(state=tk.NORMAL)
        self.progress_var.set(0)
        
        total_steps_all = self.step_mode_total_data * len(self.steps)
        
        self.log("="*60, 'info')
        self.log("🔑 MODE STEP-BY-STEP DIAKTIFKAN", 'success')
        self.log(f"📊 Total data: {self.step_mode_total_data}", 'info')
        self.log(f"📋 Total langkah per data: {len(self.steps)}", 'info')
        
        if self.use_custom_groups and self.custom_groups:
            self.log(f"📌 Mode: CUSTOM GROUPS", 'info')
            for i, group in enumerate(self.custom_groups, 1):
                self.log(f"   Group {i}: {group}", 'info')
        else:
            self.log(f"📌 Mode: UNIFORM ({self.steps_per_trigger} step per trigger)", 'info')
        
        self.log(f"🔑 Total step yang akan dijalankan: {total_steps_all}", 'info')
        self.log(f"🔑 Tekan {self.start_hotkey.upper()} untuk menjalankan setiap GROUP STEP", 'warning')
        self.log("="*60, 'info')
        
        # Ambil nilai delay dari entry
        try:
            delay = int(self.start_delay_entry.get())
            if delay < 0:
                delay = 0
        except:
            delay = 5
        
        if delay > 0:
            self.log(f"⏳ Otomasi akan dimulai dalam {delay} detik...", 'warning')
            self.update_status(f"Memulai dalam {delay} detik...")
            
            # Countdown di thread terpisah
            def countdown():
                for i in range(delay, 0, -1):
                    if not self.is_step_mode_running:
                        self.log("⏹ Mode Step-by-Step dibatalkan.", 'warning')
                        self.root.after(0, self.finish_step_mode)
                        return
                    self.update_status(f"Memulai dalam {i} detik...")
                    self.log(f"⏳ {i}...", 'info')
                    time.sleep(1)
                
                if self.is_step_mode_running:
                    self.root.after(0, self.show_current_data_info)
                    self.root.after(0, self.run_next_group_step)
            
            threading.Thread(target=countdown, daemon=True).start()
        else:
            self.log("▶️ Mode Step-by-Step dimulai langsung!", 'success')
            self.update_status("Menjalankan mode step-by-step...")
            self.show_current_data_info()
            self.run_next_group_step()

    def show_current_data_info(self):
        """Tampilkan informasi data saat ini"""
        if self.current_data_index < self.step_mode_total_data:
            row_data = self.step_mode_data[self.current_data_index]
            row_number = self.current_data_index + 1
            self.log(f"\n{'='*50}", 'info')
            self.log(f"📌 DATA {row_number}/{self.step_mode_total_data}", 'info')
            for col, value in row_data.items():
                if value and not self.is_nan_value(value):
                    self.log(f"  {col}: {value}", 'info')

    def run_next_group_step(self):
        """Menjalankan group step berikutnya (support custom groups)"""
        if not self.is_step_mode_running:
            return
        
        # Cek apakah semua data sudah selesai
        if self.current_data_index >= self.step_mode_total_data:
            self.finish_step_mode()
            return
        
        # Ambil data saat ini
        row_data = self.step_mode_data[self.current_data_index]
        row_number = self.current_data_index + 1
        total_steps = len(self.steps)
        
        # Tentukan steps yang akan dijalankan di group ini
        steps_to_run = []
        group_display = ""
        
        if self.use_custom_groups and self.custom_groups:
            # Custom groups mode
            group_found = False
            for group in self.custom_groups:
                # Cari group yang mengandung current_step_index + 1
                current_step = self.current_step_index + 1
                if current_step in group:
                    steps_to_run = [s - 1 for s in group if s > self.current_step_index]
                    group_display = f"Group {self.custom_groups.index(group) + 1}: {group}"
                    group_found = True
                    break
            
            if not group_found:
                # Jika tidak ada group yang cocok, pindah ke data berikutnya
                self.current_data_index += 1
                self.current_step_index = 0
                
                if self.current_data_index >= self.step_mode_total_data:
                    self.finish_step_mode()
                    return
                
                self.show_current_data_info()
                self.root.after(100, self.run_next_group_step)
                return
        
        else:
            # Uniform mode: jalankan steps_per_trigger step
            try:
                steps_per = int(self.steps_per_trigger_entry.get())
                if steps_per < 1:
                    steps_per = 1
            except:
                steps_per = 2
            
            steps_remaining = total_steps - self.current_step_index
            steps_count = min(steps_per, steps_remaining)
            steps_to_run = list(range(self.current_step_index, self.current_step_index + steps_count))
            group_display = f"Step {self.current_step_index + 1}-{self.current_step_index + steps_count}"
        
        if not steps_to_run:
            # Tidak ada step yang dijalankan, pindah ke data berikutnya
            self.current_data_index += 1
            self.current_step_index = 0
            
            if self.current_data_index >= self.step_mode_total_data:
                self.finish_step_mode()
                return
            
            self.show_current_data_info()
            self.root.after(100, self.run_next_group_step)
            return
        
        # Tampilkan informasi group step
        self.log(f"\n{'─'*40}", 'info')
        self.log(f"🔑 {group_display} - Data {row_number}/{self.step_mode_total_data}", 'step')
        self.update_status(f"🔑 Menjalankan {group_display} - Data {row_number}/{self.step_mode_total_data}")
        
        # Jalankan semua step dalam group ini
        all_success = True
        for step_idx in steps_to_run:
            if step_idx >= len(self.steps):
                break
                
            step = self.steps[step_idx]
            step_num = step_idx + 1
            
            if not self.is_step_mode_running:
                break
            
            if not step.enabled:
                self.log(f"  ⏭️ Step {step_num} tidak aktif, skip...", 'warning')
                self.current_step_index += 1
                continue
            
            self.log(f"  ▶ Step {step_num}: {step.name}", 'step')
            if not self.execute_step(step, row_data, row_number, self.step_mode_total_data):
                all_success = False
                self.log(f"  ❌ Step {step_num} GAGAL!", 'error')
            else:
                self.log(f"  ✅ Step {step_num} SUKSES!", 'success')
            
            self.current_step_index += 1
        
        # Update progress
        total_steps_all = self.step_mode_total_data * len(self.steps)
        steps_done = (self.current_data_index * len(self.steps)) + self.current_step_index
        self.update_progress(steps_done, total_steps_all)
        
        # Cek apakah semua data selesai
        if self.current_data_index >= self.step_mode_total_data and self.current_step_index >= len(self.steps):
            self.finish_step_mode()
            return
        
        # Cek apakah perlu pindah ke data berikutnya
        if self.current_step_index >= len(self.steps):
            self.current_data_index += 1
            self.current_step_index = 0
            
            if self.current_data_index >= self.step_mode_total_data:
                self.finish_step_mode()
                return
            
            self.show_current_data_info()
            self.log(f"\n⏸️ MENUNGGU TRIGGER KEY UNTUK DATA BERIKUTNYA", 'warning')
            self.log(f"🔑 Tekan {self.start_hotkey.upper()} untuk mulai Data {self.current_data_index + 1}", 'warning')
            self.update_status(f"🔑 Tekan {self.start_hotkey.upper()} untuk Data {self.current_data_index + 1}")
        else:
            # Tampilkan group berikutnya
            next_step = self.current_step_index + 1
            if self.use_custom_groups and self.custom_groups:
                # Cari group berikutnya
                next_group = None
                for group in self.custom_groups:
                    if next_step in group:
                        next_group = group
                        break
                if next_group:
                    self.log(f"\n⏸️ MENUNGGU TRIGGER KEY UNTUK GROUP BERIKUTNYA", 'warning')
                    self.log(f"🔑 Tekan {self.start_hotkey.upper()} untuk Group: {next_group}", 'warning')
                    self.update_status(f"🔑 Tekan {self.start_hotkey.upper()} untuk Group: {next_group}")
                else:
                    self.log(f"\n⏸️ MENUNGGU TRIGGER KEY UNTUK STEP BERIKUTNYA", 'warning')
                    self.log(f"🔑 Tekan {self.start_hotkey.upper()} untuk Step {next_step}", 'warning')
                    self.update_status(f"🔑 Tekan {self.start_hotkey.upper()} untuk Step {next_step}")
            else:
                try:
                    steps_per = int(self.steps_per_trigger_entry.get())
                    if steps_per < 1:
                        steps_per = 1
                except:
                    steps_per = 2
                next_end = min(next_step + steps_per - 1, len(self.steps))
                self.log(f"\n⏸️ MENUNGGU TRIGGER KEY UNTUK GROUP BERIKUTNYA", 'warning')
                self.log(f"🔑 Tekan {self.start_hotkey.upper()} untuk Step {next_step}-{next_end}", 'warning')
                self.update_status(f"🔑 Tekan {self.start_hotkey.upper()} untuk Step {next_step}-{next_end}")

    def finish_step_mode(self):
        """Menyelesaikan mode step-by-step"""
        self.is_step_mode_running = False
        self.is_running = False
        
        self.log("\n" + "="*50, 'info')
        self.log("🏁 STEP-BY-STEP SELESAI!", 'success')
        self.log(f"✓ Total data diproses: {self.step_mode_total_data}", 'success')
        
        self.update_status("Selesai! Mode Step-by-Step selesai")
        
        self.start_btn.config(state=tk.NORMAL)
        self.step_mode_btn.config(state=tk.NORMAL)
        self.stop_btn.config(state=tk.DISABLED)
        self.progress_var.set(100)

    def trigger_next_step(self):
        """Dipanggil ketika trigger key ditekan dalam mode step-by-step"""
        if self.is_step_mode_running:
            # Jalankan group step berikutnya di thread terpisah agar tidak block UI
            threading.Thread(target=self.run_next_group_step, daemon=True).start()
        else:
            # Jika belum aktif, mulai mode step-by-step
            self.start_step_mode()
    
    def stop_automation(self):
        if self.is_running:
            self.is_running = False
            self.log("⏹ Menghentikan otomasi...", 'warning')
            self.update_status("Menghentikan...")
            self.stop_btn.config(state=tk.DISABLED)
            time.sleep(1)
            self.log("Otomasi dihentikan.", 'warning')
            # Pop up dihilangkan
            # messagebox.showinfo("Info", "Otomasi telah dihentikan oleh user.")
        
        # Juga stop mode step-by-step
        if self.is_step_mode_running:
            self.is_step_mode_running = False
            self.log("⏹ Mode Step-by-Step dihentikan.", 'warning')
            self.start_btn.config(state=tk.NORMAL)
            self.step_mode_btn.config(state=tk.NORMAL)
            self.stop_btn.config(state=tk.DISABLED)
            self.update_status("Siap menjalankan otomasi...")

    def trigger_start_automation(self):
        """Dipanggil ketika trigger key ditekan"""
        if self.is_step_mode_running:
            # Jika dalam mode step-by-step, lanjutkan ke step berikutnya
            self.root.after(0, self.trigger_next_step)
        elif not self.is_running:
            # Jika tidak running, cek apakah ada file dan steps
            if not self.excel_file_path.get():
                self.log("⚠️ Silakan pilih file Excel terlebih dahulu!", 'warning')
                return
            if not self.steps:
                self.log("⚠️ Tambahkan langkah-langkah otomasi terlebih dahulu!", 'warning')
                return
            
            # Mulai mode step-by-step (tanpa perlu klik tombol)
            self.root.after(0, self.start_step_mode)

    # ==================== CUSTOM GROUPS METHODS ====================

    def show_custom_groups_dialog(self):
        """Dialog untuk mengatur kelompok langkah custom"""
        dialog = tk.Toplevel(self.root)
        dialog.title("Atur Kelompok Langkah (Custom Groups)")
        dialog.geometry("550x500")
        dialog.transient(self.root)
        dialog.grab_set()
        
        main_frame = ttk.Frame(dialog, padding="15")
        main_frame.pack(fill=tk.BOTH, expand=True)
        
        ttk.Label(main_frame, text="Atur Kelompok Langkah per Trigger", 
                  font=('Arial', 12, 'bold')).pack(pady=(0, 10))
        
        # Info
        info_frame = ttk.Frame(main_frame)
        info_frame.pack(fill=tk.X, pady=(0, 10))
        ttk.Label(info_frame, text=f"Total Langkah: {len(self.steps)}", 
                  font=('Arial', 10)).pack(side=tk.LEFT, padx=5)
        ttk.Label(info_frame, text="Contoh: 1,2,3 | 4,5 | 6", 
                  font=('Arial', 9), foreground='gray').pack(side=tk.RIGHT, padx=5)
        
        # Listbox untuk menampilkan groups
        listbox_frame = ttk.Frame(main_frame)
        listbox_frame.pack(fill=tk.BOTH, expand=True, pady=5)
        
        groups_listbox = tk.Listbox(listbox_frame, height=8, font=('Courier', 10))
        groups_listbox.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        
        groups_scroll = ttk.Scrollbar(listbox_frame, orient=tk.VERTICAL, command=groups_listbox.yview)
        groups_scroll.pack(side=tk.RIGHT, fill=tk.Y)
        groups_listbox.config(yscrollcommand=groups_scroll.set)
        
        # Entry untuk input group
        input_frame = ttk.Frame(main_frame)
        input_frame.pack(fill=tk.X, pady=5)
        
        ttk.Label(input_frame, text="Nomor Langkah:").pack(side=tk.LEFT, padx=(0, 5))
        group_entry = ttk.Entry(input_frame, width=25)
        group_entry.pack(side=tk.LEFT, padx=5)
        ttk.Button(input_frame, text="Tambah", 
                   command=lambda: self.add_custom_group(group_entry.get(), groups_listbox)).pack(side=tk.LEFT, padx=2)
        ttk.Button(input_frame, text="Tambah Range (1-3)", 
                   command=lambda: self.add_custom_group_range(group_entry.get(), groups_listbox)).pack(side=tk.LEFT, padx=2)
        
        # Tombol aksi
        btn_frame = ttk.Frame(main_frame)
        btn_frame.pack(fill=tk.X, pady=10)
        
        ttk.Button(btn_frame, text="Hapus Group", 
                   command=lambda: self.delete_custom_group(groups_listbox)).pack(side=tk.LEFT, padx=5)
        ttk.Button(btn_frame, text="Hapus Semua", 
                   command=lambda: self.clear_custom_groups(groups_listbox)).pack(side=tk.LEFT, padx=5)
        ttk.Button(btn_frame, text="Auto Generate", 
                   command=lambda: self.auto_generate_groups(groups_listbox)).pack(side=tk.LEFT, padx=5)
        
        # Status
        status_label = ttk.Label(main_frame, text=f"Total {len(self.custom_groups)} kelompok", 
                                 font=('Arial', 9))
        status_label.pack(pady=5)
        
        # Load groups yang sudah ada
        self.load_groups_to_listbox(groups_listbox)
        
        # Tombol simpan
        save_frame = ttk.Frame(main_frame)
        save_frame.pack(fill=tk.X, pady=10)
        
        use_custom_var = tk.BooleanVar(value=self.use_custom_groups)
        ttk.Checkbutton(save_frame, text="Gunakan Custom Groups", 
                        variable=use_custom_var).pack(side=tk.LEFT, padx=5)
        
        ttk.Button(save_frame, text="Simpan & Tutup", 
                   command=lambda: self.save_custom_groups(dialog, groups_listbox, use_custom_var)).pack(side=tk.RIGHT, padx=5)
        ttk.Button(save_frame, text="Batal", command=dialog.destroy).pack(side=tk.RIGHT, padx=5)

    def add_custom_group(self, input_text, listbox):
        """Tambah group baru dari input text (format: 1,2,3)"""
        try:
            if not input_text.strip():
                messagebox.showwarning("Peringatan", "Masukkan nomor langkah!")
                return
            
            # Parse input: "1,2,3" atau "1 2 3"
            steps = []
            for part in input_text.replace(',', ' ').split():
                if part.strip():
                    steps.append(int(part.strip()))
            
            # Validasi step numbers
            for step in steps:
                if step < 1 or step > len(self.steps):
                    messagebox.showerror("Error", f"Langkah {step} tidak valid! Total langkah: {len(self.steps)}")
                    return
            
            # Cek duplikat
            for existing in self.custom_groups:
                if set(existing) == set(steps):
                    messagebox.showwarning("Peringatan", "Group ini sudah ada!")
                    return
            
            self.custom_groups.append(steps)
            self.load_groups_to_listbox(listbox)
            # Clear entry
            for child in listbox.master.master.winfo_children():
                if isinstance(child, ttk.Frame):
                    for subchild in child.winfo_children():
                        if isinstance(subchild, ttk.Entry):
                            subchild.delete(0, tk.END)
            self.log(f"✅ Group ditambahkan: {steps}", 'success')
            
        except ValueError:
            messagebox.showerror("Error", "Format tidak valid! Gunakan: 1,2,3")

    def add_custom_group_range(self, input_text, listbox):
        """Tambah group baru dari range (format: 1-3)"""
        try:
            if not input_text.strip():
                messagebox.showwarning("Peringatan", "Masukkan range langkah! Contoh: 1-3")
                return
            
            if '-' not in input_text:
                messagebox.showwarning("Peringatan", "Gunakan format range! Contoh: 1-3")
                return
            
            parts = input_text.split('-')
            start = int(parts[0].strip())
            end = int(parts[1].strip())
            
            if start > end:
                start, end = end, start
            
            steps = list(range(start, end + 1))
            
            # Validasi step numbers
            for step in steps:
                if step < 1 or step > len(self.steps):
                    messagebox.showerror("Error", f"Langkah {step} tidak valid! Total langkah: {len(self.steps)}")
                    return
            
            self.custom_groups.append(steps)
            self.load_groups_to_listbox(listbox)
            # Clear entry
            for child in listbox.master.master.winfo_children():
                if isinstance(child, ttk.Frame):
                    for subchild in child.winfo_children():
                        if isinstance(subchild, ttk.Entry):
                            subchild.delete(0, tk.END)
            self.log(f"✅ Group range ditambahkan: {steps}", 'success')
            
        except ValueError:
            messagebox.showerror("Error", "Format tidak valid! Gunakan: 1-3")

    def delete_custom_group(self, listbox):
        """Hapus group yang dipilih"""
        selected = listbox.curselection()
        if selected:
            idx = selected[0]
            del self.custom_groups[idx]
            self.load_groups_to_listbox(listbox)
            self.log(f"🗑 Group dihapus", 'warning')
        else:
            messagebox.showwarning("Peringatan", "Pilih group yang akan dihapus!")

    def clear_custom_groups(self, listbox):
        """Hapus semua group"""
        if self.custom_groups:
            if messagebox.askyesno("Konfirmasi", "Hapus semua kelompok?"):
                self.custom_groups = []
                self.load_groups_to_listbox(listbox)
                self.log("🗑 Semua group dihapus", 'warning')

    def auto_generate_groups(self, listbox):
        """Auto generate groups berdasarkan steps_per_trigger"""
        if not self.steps:
            messagebox.showwarning("Peringatan", "Tidak ada langkah!")
            return
        
        try:
            steps_per = int(self.steps_per_trigger_entry.get())
            if steps_per < 1:
                steps_per = 1
        except:
            steps_per = 2
        
        total_steps = len(self.steps)
        self.custom_groups = []
        
        for i in range(1, total_steps + 1, steps_per):
            end = min(i + steps_per - 1, total_steps)
            self.custom_groups.append(list(range(i, end + 1)))
        
        self.load_groups_to_listbox(listbox)
        self.log(f"✅ Auto generate {len(self.custom_groups)} groups", 'success')

    def load_groups_to_listbox(self, listbox):
        """Load groups ke listbox"""
        listbox.delete(0, tk.END)
        for i, group in enumerate(self.custom_groups, 1):
            listbox.insert(tk.END, f"Group {i}: {group}")

    def save_custom_groups(self, dialog, listbox, use_custom_var):
        """Simpan dan tutup dialog"""
        self.use_custom_groups = use_custom_var.get()
        self.load_groups_to_listbox(listbox)
        
        if self.use_custom_groups and self.custom_groups:
            # Validasi apakah semua step tercakup
            all_steps = []
            for group in self.custom_groups:
                all_steps.extend(group)
            total_steps = len(self.steps)
            
            if set(all_steps) != set(range(1, total_steps + 1)):
                if not messagebox.askyesno("Peringatan", 
                    "Custom groups tidak mencakup semua langkah!\n"
                    f"Langkah yang ada: 1-{total_steps}\n"
                    f"Langkah di groups: {sorted(set(all_steps))}\n\n"
                    "Tetap gunakan custom groups?"):
                    return
        
        self.log(f"✅ Custom groups disimpan: {len(self.custom_groups)} kelompok", 'success')
        if self.use_custom_groups:
            self.log(f"   Mode: Custom Groups aktif", 'info')
        else:
            self.log(f"   Mode: Uniform (sesuai Langkah per Trigger)", 'info')
        dialog.destroy()

    def set_trigger_key(self):
        """Mengubah trigger key untuk memulai otomasi"""
        new_key = self.trigger_key_entry.get().strip().lower()
        if not new_key:
            messagebox.showwarning("Peringatan", "Key tidak boleh kosong!")
            return
        
        try:
            # Hapus hotkey lama
            keyboard.remove_hotkey(self.start_hotkey)
            
            # Set hotkey baru
            self.start_hotkey = new_key
            keyboard.add_hotkey(self.start_hotkey, self.trigger_start_automation)
            
            self.log(f"✅ Trigger key diubah menjadi: {self.start_hotkey.upper()}", 'success')
            self.update_status(f"Trigger key: {self.start_hotkey.upper()}")
            
            # Update entry
            self.trigger_key_entry.delete(0, tk.END)
            self.trigger_key_entry.insert(0, self.start_hotkey)
            
        except Exception as e:
            self.log(f"❌ Error setting trigger key: {str(e)}", 'error')
            messagebox.showerror("Error", f"Gagal mengatur trigger key: {str(e)}")  

    def save_config(self):
        try:
            config = {
                'steps': [],
                'settings': {
                    'start_row': self.start_row_entry.get() if self.start_row_entry.winfo_exists() else "1",
                    'default_delay': self.default_delay_entry.get() if self.default_delay_entry.winfo_exists() else "1",
                    'start_delay': self.start_delay_entry.get() if self.start_delay_entry.winfo_exists() else "5",
                    'trigger_key': self.start_hotkey,
                    'steps_per_trigger': self.steps_per_trigger_entry.get() if self.steps_per_trigger_entry.winfo_exists() else "2",
                    'custom_groups': self.custom_groups,
                    'use_custom_groups': self.use_custom_groups
                }
            }
            
            for step in self.steps:
                step_data = {
                    'type': step.step_type,
                    'name': step.name,
                    'position': step.position,
                    'delay': step.delay,
                    'text': step.text,
                    'key_combination': step.key_combination,
                    'scroll_amount': step.scroll_amount,
                    'drag_to': step.drag_to,
                    'enabled': step.enabled,
                    'type_mode': step.type_mode
                }
                config['steps'].append(step_data)
            
            file_path = filedialog.asksaveasfilename(
                defaultextension=".json",
                filetypes=[("JSON files", "*.json"), ("All files", "*.*")],
                initialfile="config_otomasi.json"
            )
            
            if file_path:
                try:
                    with open(file_path, 'w') as f:
                        json.dump(config, f, indent=4)
                    self.log(f"Config saved: {file_path}", 'success')
                except Exception as e:
                    self.log(f"Error saving config: {str(e)}", 'error')
        except Exception as e:
            self.log(f"Error in save_config: {str(e)}", 'error')
            messagebox.showerror("Error", f"Gagal menyimpan config: {str(e)}")
    
    def load_config_dialog(self):
        file_path = filedialog.askopenfilename(
            title="Load Config",
            filetypes=[("JSON files", "*.json"), ("All files", "*.*")]
        )
        if file_path:
            self.load_config(file_path)
    
    def load_config(self, file_path=None):
        if not file_path:
            return

        try:
            with open(file_path, 'r') as f:
                config = json.load(f)
            
            # Hapus semua steps lama
            self.steps = []
            for step_data in config.get('steps', []):
                step = Step(
                    step_type=step_data.get('type', 'click'),
                    name=step_data.get('name', ''),
                    position=tuple(step_data['position']) if step_data.get('position') else None,
                    delay=step_data.get('delay', 0.5),
                    text=step_data.get('text', ''),
                    key_combination=step_data.get('key_combination', ''),
                    scroll_amount=step_data.get('scroll_amount', 0),
                    drag_to=tuple(step_data['drag_to']) if step_data.get('drag_to') else None,
                    type_mode=step_data.get('type_mode', 'all')
                )
                step.enabled = step_data.get('enabled', True)
                self.steps.append(step)
            
            settings = config.get('settings', {})
            if 'start_row' in settings:
                self.start_row_entry.delete(0, tk.END)
                self.start_row_entry.insert(0, settings['start_row'])
            if 'default_delay' in settings:
                self.default_delay_entry.delete(0, tk.END)
                self.default_delay_entry.insert(0, settings['default_delay'])
            if 'start_delay' in settings:
                self.start_delay_entry.delete(0, tk.END)
                self.start_delay_entry.insert(0, settings['start_delay'])
            if 'trigger_key' in settings:
                self.trigger_key_entry.delete(0, tk.END)
                self.trigger_key_entry.insert(0, settings['trigger_key'])
                # Update trigger key
                try:
                    keyboard.remove_hotkey(self.start_hotkey)
                    self.start_hotkey = settings['trigger_key']
                    keyboard.add_hotkey(self.start_hotkey, self.trigger_start_automation)
                except:
                    pass
            if 'steps_per_trigger' in settings:
                self.steps_per_trigger_entry.delete(0, tk.END)
                self.steps_per_trigger_entry.insert(0, settings['steps_per_trigger'])
            if 'custom_groups' in settings:
                self.custom_groups = settings['custom_groups']
            if 'use_custom_groups' in settings:
                self.use_custom_groups = settings['use_custom_groups']
            
            # Update mapping UI (read-only)
            self.update_mapping_ui()
            
            self.update_step_list()
            self.log(f"Config loaded: {file_path}", 'success')
            self.log(f"{len(self.steps)} langkah dimuat", 'info')
            if self.use_custom_groups and self.custom_groups:
                self.log(f"Custom Groups: {len(self.custom_groups)} kelompok", 'info')
            
        except Exception as e:
            self.log(f"Error loading config: {str(e)}", 'error')

    def duplicate_step(self):
        """Menduplikat langkah yang dipilih"""
        selected = self.step_listbox.curselection()
        if not selected:
            messagebox.showwarning("Peringatan", "Silakan pilih langkah yang ingin diduplikat!")
            return
        
        idx = selected[0]
        original_step = self.steps[idx]
        
        # Buat salinan mendalam dari langkah
        new_step = Step(
            step_type=original_step.step_type,
            name=f"{original_step.name} (Copy)",
            position=original_step.position.copy() if original_step.position else None,
            delay=original_step.delay,
            text=original_step.text,
            key_combination=original_step.key_combination,
            scroll_amount=original_step.scroll_amount,
            drag_to=original_step.drag_to.copy() if original_step.drag_to else None,
            type_mode=original_step.type_mode
        )
        new_step.enabled = original_step.enabled
        
        # Insert di posisi setelah langkah yang dipilih
        self.steps.insert(idx + 1, new_step)
        self.update_step_list()
        
        # Select langkah yang baru diduplikat
        self.step_listbox.selection_set(idx + 1)
        self.step_listbox.see(idx + 1)
        
        self.log(f"✅ Langkah diduplikat: {original_step.name} → {new_step.name}", 'success')
    
    def toggle_step_selection(self, index):
        pass

    def select_all_steps(self):
        """Pilih semua langkah"""
        self.step_listbox.selection_set(0, tk.END)
        count = len(self.steps)
        self.update_status(f"{count} langkah dipilih")
        self.log(f"✅ {count} langkah dipilih", 'info')

    def deselect_all_steps(self):
        """Batalkan pilihan semua langkah"""
        self.step_listbox.selection_clear(0, tk.END)
        self.update_status("Siap menjalankan otomasi...")
        self.log("Pilihan langkah dibatalkan", 'info')

    def duplicate_selected_steps(self):
        """Duplikat semua langkah yang dipilih"""
        selected_indices = self.step_listbox.curselection()
        if not selected_indices:
            messagebox.showwarning("Peringatan", "Silakan pilih langkah yang ingin diduplikat!")
            return
        
        # Konversi ke list dan urutkan descending (dari belakang ke depan)
        selected_list = list(selected_indices)
        selected_list.sort(reverse=True)
        
        duplicated_count = 0
        for idx in selected_list:
            try:
                original_step = self.steps[idx]
                
                # Buat salinan langkah
                new_step = Step(
                    step_type=original_step.step_type,
                    name=f"{original_step.name} (Copy)",
                    position=tuple(original_step.position) if original_step.position else None,
                    delay=original_step.delay,
                    text=original_step.text,
                    key_combination=original_step.key_combination,
                    scroll_amount=original_step.scroll_amount,
                    drag_to=tuple(original_step.drag_to) if original_step.drag_to else None,
                    type_mode=original_step.type_mode
                )
                new_step.enabled = original_step.enabled
                
                # Insert di posisi setelah langkah yang dipilih
                self.steps.insert(idx + 1, new_step)
                duplicated_count += 1
            except Exception as e:
                self.log(f"Error duplikat langkah {idx}: {str(e)}", 'error')
        
        # Update listbox
        self.update_step_list()
        
        # Select langkah-langkah yang baru diduplikat
        new_indices = []
        for idx in selected_list:
            new_indices.append(idx + 1)
        
        for new_idx in new_indices:
            if new_idx < len(self.steps):
                self.step_listbox.selection_set(new_idx)
        
        self.log(f"✅ {duplicated_count} langkah diduplikat", 'success')
        self.update_status(f"{duplicated_count} langkah diduplikat")

    def delete_selected_steps(self):
        """Hapus semua langkah yang dipilih"""
        selected_indices = self.step_listbox.curselection()
        if not selected_indices:
            messagebox.showwarning("Peringatan", "Silakan pilih langkah yang ingin dihapus!")
            return
        
        # Konfirmasi
        count = len(selected_indices)
        if not messagebox.askyesno("Konfirmasi", f"Hapus {count} langkah yang dipilih?"):
            return
        
        # Konversi ke list dan urutkan descending (dari belakang ke depan)
        selected_list = list(selected_indices)
        selected_list.sort(reverse=True)
        
        # Hapus langkah (dari belakang agar index tidak berubah)
        deleted_count = 0
        for idx in selected_list:
            try:
                self.steps.pop(idx)
                deleted_count += 1
            except IndexError:
                pass
        
        self.update_step_list()
        self.log(f"🗑 {deleted_count} langkah dihapus", 'warning')
        self.update_status(f"{deleted_count} langkah dihapus")
    
    def on_step_selection(self, event):
        """Handler ketika seleksi listbox berubah"""
        selected = self.step_listbox.curselection()
        if selected:
            count = len(selected)
            self.update_status(f"{count} langkah dipilih")
        else:
            self.update_status("Siap menjalankan otomasi...")
    
    def move_selected_up(self):
        """Pindahkan semua langkah yang dipilih ke atas"""
        selected_indices = list(self.step_listbox.curselection())
        if not selected_indices:
            messagebox.showwarning("Peringatan", "Silakan pilih langkah yang ingin dipindahkan!")
            return
        
        # Cek apakah bisa dipindah ke atas
        if min(selected_indices) == 0:
            messagebox.showinfo("Info", "Langkah sudah di posisi paling atas!")
            return
        
        # Urutkan ascending (dari kecil ke besar) untuk dipindah ke atas
        selected_indices.sort()
        
        # Pindahkan ke atas satu per satu
        for idx in selected_indices:
            if idx > 0:
                self.steps[idx], self.steps[idx-1] = self.steps[idx-1], self.steps[idx]
        
        # Update listbox
        self.update_step_list()
        
        # Select ulang langkah-langkah yang dipindahkan
        new_indices = [idx - 1 for idx in selected_indices if idx > 0]
        for new_idx in new_indices:
            if new_idx >= 0:
                self.step_listbox.selection_set(new_idx)
        
        self.log(f"⬆ {len(new_indices)} langkah dipindahkan ke atas", 'info')
        self.update_status(f"{len(new_indices)} langkah dipindahkan ke atas")

    def move_selected_down(self):
        """Pindahkan semua langkah yang dipilih ke bawah"""
        selected_indices = list(self.step_listbox.curselection())
        if not selected_indices:
            messagebox.showwarning("Peringatan", "Silakan pilih langkah yang ingin dipindahkan!")
            return
        
        # Cek apakah bisa dipindah ke bawah
        if max(selected_indices) >= len(self.steps) - 1:
            messagebox.showinfo("Info", "Langkah sudah di posisi paling bawah!")
            return
        
        # Urutkan descending (dari besar ke kecil) untuk dipindah ke bawah
        selected_indices.sort(reverse=True)
        
        # Pindahkan ke bawah satu per satu
        for idx in selected_indices:
            if idx < len(self.steps) - 1:
                self.steps[idx], self.steps[idx+1] = self.steps[idx+1], self.steps[idx]
        
        # Update listbox
        self.update_step_list()
        
        # Select ulang langkah-langkah yang dipindahkan
        new_indices = [idx + 1 for idx in selected_indices if idx < len(self.steps) - 1]
        for new_idx in new_indices:
            if new_idx < len(self.steps):
                self.step_listbox.selection_set(new_idx)
        
        self.log(f"⬇ {len(new_indices)} langkah dipindahkan ke bawah", 'info')
        self.update_status(f"{len(new_indices)} langkah dipindahkan ke bawah")

    def move_selected_to_top(self):
        """Pindahkan semua langkah yang dipilih ke posisi paling atas"""
        selected_indices = list(self.step_listbox.curselection())
        if not selected_indices:
            messagebox.showwarning("Peringatan", "Silakan pilih langkah yang ingin dipindahkan!")
            return
        
        # Cek apakah sudah di atas
        if min(selected_indices) == 0 and len(selected_indices) == 1:
            messagebox.showinfo("Info", "Langkah sudah di posisi paling atas!")
            return
        
        # Urutkan ascending
        selected_indices.sort()
        
        # Ambil langkah-langkah yang dipilih
        selected_steps = [self.steps[idx] for idx in selected_indices]
        
        # Hapus langkah-langkah yang dipilih (dari belakang ke depan)
        for idx in reversed(selected_indices):
            self.steps.pop(idx)
        
        # Insert di posisi paling atas
        for i, step in enumerate(selected_steps):
            self.steps.insert(i, step)
        
        # Update listbox
        self.update_step_list()
        
        # Select langkah-langkah yang dipindahkan
        for i in range(len(selected_steps)):
            self.step_listbox.selection_set(i)
        
        self.log(f"⬆ {len(selected_steps)} langkah dipindahkan ke paling atas", 'info')
        self.update_status(f"{len(selected_steps)} langkah dipindahkan ke paling atas")

    def move_selected_to_bottom(self):
        """Pindahkan semua langkah yang dipilih ke posisi paling bawah"""
        selected_indices = list(self.step_listbox.curselection())
        if not selected_indices:
            messagebox.showwarning("Peringatan", "Silakan pilih langkah yang ingin dipindahkan!")
            return
        
        # Cek apakah sudah di bawah
        if max(selected_indices) == len(self.steps) - 1 and len(selected_indices) == 1:
            messagebox.showinfo("Info", "Langkah sudah di posisi paling bawah!")
            return
        
        # Urutkan descending (dari belakang ke depan)
        selected_indices.sort(reverse=True)
        
        # Ambil langkah-langkah yang dipilih
        selected_steps = [self.steps[idx] for idx in selected_indices]
        
        # Hapus langkah-langkah yang dipilih (dari belakang ke depan)
        for idx in selected_indices:
            self.steps.pop(idx)
        
        # Insert di posisi paling bawah
        start_idx = len(self.steps)
        for i, step in enumerate(selected_steps):
            self.steps.insert(start_idx + i, step)
        
        # Update listbox
        self.update_step_list()
        
        # Select langkah-langkah yang dipindahkan
        for i in range(len(selected_steps)):
            self.step_listbox.selection_set(start_idx + i)
        
        self.log(f"⬇ {len(selected_steps)} langkah dipindahkan ke paling bawah", 'info')
        self.update_status(f"{len(selected_steps)} langkah dipindahkan ke paling bawah")

def main():
    root = tk.Tk()
    app = AutomationApp(root)
    root.mainloop()

if __name__ == "__main__":
    main()