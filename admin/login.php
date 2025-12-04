<?php
include '../config/database.php'; // Koneksi ke $conn
session_start();
$login_message = "";

// ----------------------------------------------------
// LOGIKA LOGIN HARUS DI ATAS SEMUA OUTPUT
// ----------------------------------------------------
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Ambil SEMUA data user, termasuk role, name, dan password
    $sql = "SELECT id, name, email, password, role FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) { // Hanya boleh ada 1 hasil
        $data = $result->fetch_assoc();

        // SET VARIABEL SESI
        $_SESSION["user_id"] = $data["id"];
        $_SESSION["user_name"] = $data["name"];
        $_SESSION["user_role"] = $data["role"]; // Simpan role untuk otorisasi
        $_SESSION["is_login"] = true;

        $conn->close();
        header("Location: dashboard.php"); // Ganti dashboard.php
        exit(); // Penting: Hentikan eksekusi
    } else {
        $login_message = "Login Gagal. Email atau Password salah.";
    }
}
$conn->close(); // Tutup koneksi jika login gagal/atau saat halaman dimuat biasa
?>
    <!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Online Shop</title><!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script><!-- Canva SDK -->
  <script src="/_sdk/element_sdk.js"></script>
  <style>
        body {
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        .app-wrapper {
            width: 100%;
            height: 100%;
            min-height: 100%;
        }
        
        /* Custom focus styles */
        .focus-ring:focus {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }
        
        /* Shopping bag icon */
        .shop-icon {
            width: 40px;
            height: 40px;
        }
    </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body>
  <div class="app-wrapper" id="app-root">
   <div class="w-full h-full flex"><!-- Left Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12" id="left-panel">
     <div class="w-full max-w-md"><!-- Logo & Brand -->
      <div class="mb-8">
       <div class="flex items-center gap-3 mb-6">
        <svg class="shop-icon" id="logo-icon" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /> <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /> <path d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg><span class="text-2xl font-bold" id="brand-name">ShopHub</span>
       </div>
       <h1 class="text-3xl font-bold mb-2" id="page-title">Login to Your Account</h1>
       <p class="opacity-80" id="page-subtitle">Welcome back! Please enter your details</p>
      </div><!-- Error Message (if any) -->
      <div id="error-message" class="hidden mb-4 p-3 rounded-lg border">
       <p class="text-sm" id="error-text"></p>
      </div><!-- Login Form -->
      <form action="login.php" method="POST" id="login-form">
       <div class="space-y-4"><!-- Email Field -->
        <div><label for="email" class="block text-sm font-medium mb-2" id="email-label-el">Email Address</label> <input type="email" id="email" name="email" required class="focus-ring w-full px-4 py-3 rounded-lg border transition-colors" placeholder="you@example.com">
        </div><!-- Password Field -->
        <div>
         <div class="flex items-center justify-between mb-2"><label for="password" class="block text-sm font-medium" id="password-label-el">Password</label> <a href="forgot-password.php" class="text-sm hover:underline" id="forgot-link">Forgot Password?</a>
         </div><input type="password" id="password" name="password" required class="focus-ring w-full px-4 py-3 rounded-lg border transition-colors" placeholder="••••••••">
        </div><!-- Remember Me -->
        <div class="flex items-center"><input type="checkbox" id="remember" name="remember" class="focus-ring w-4 h-4 rounded"> <label for="remember" class="ml-2 text-sm">Remember me</label>
        </div><!-- Submit Button --> <button type="submit" name="login" id="login-button" class="focus-ring w-full py-3 rounded-lg font-semibold transition-all transform hover:scale-[1.02] active:scale-[0.98]"> <span id="login-button-text">Sign In</span> </button>
       </div>
      </form><!-- Register Link -->
      <div class="mt-6 text-center">
       <p class="text-sm"><span id="register-prompt">Don't have an account?</span> <a href="register.php" class="font-semibold hover:underline ml-1" id="register-link">Sign Up</a></p>
      </div><!-- Divider -->
      <div class="relative my-6">
       <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t" id="divider"></div>
       </div>
       <div class="relative flex justify-center text-sm"><span class="px-2" id="divider-text">Or continue with</span>
       </div>
      </div><!-- Social Login -->
      <div class="grid grid-cols-2 gap-3"><button type="button" class="focus-ring flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border transition-colors hover:opacity-80" id="google-btn">
        <svg width="20" height="20" viewbox="0 0 24 24" fill="none"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" /> <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" /> <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" /> <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
        </svg><span class="text-sm font-medium">Google</span> </button> <button type="button" class="focus-ring flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border transition-colors hover:opacity-80" id="facebook-btn">
        <svg width="20" height="20" viewbox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
        </svg><span class="text-sm font-medium">Facebook</span> </button>
      </div><!-- Footer -->
      <footer class="mt-8 text-center">
       <p class="text-xs opacity-60" id="footer-text">© 2024 Your Shop. All rights reserved.</p>
      </footer>
     </div>
    </div><!-- Right Side - Image/Illustration -->
    <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 relative overflow-hidden" id="right-panel">
     <div class="relative z-10 text-center max-w-lg"><!-- Shopping illustration using SVG -->
      <svg class="w-full max-w-md mx-auto mb-8" viewbox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg"><!-- Shopping bags --> <rect x="80" y="120" width="100" height="120" rx="8" id="bag1" opacity="0.9" /> <rect x="80" y="120" width="100" height="30" id="bag1-top" /> <path d="M110 120 C110 105, 150 105, 150 120" stroke="white" stroke-width="3" fill="none" opacity="0.7" /> <rect x="220" y="100" width="100" height="140" rx="8" id="bag2" opacity="0.9" /> <rect x="220" y="100" width="100" height="30" id="bag2-top" /> <path d="M250 100 C250 85, 290 85, 290 100" stroke="white" stroke-width="3" fill="none" opacity="0.7" /> <!-- Decorative circles --> <circle cx="60" cy="60" r="30" id="circle1" opacity="0.6" /> <circle cx="340" cy="240" r="40" id="circle2" opacity="0.6" /> <circle cx="350" cy="80" r="20" id="circle3" opacity="0.5" />
      </svg>
      
      <h2 class="text-4xl font-bold mb-4" id="promo-title">Start Shopping Today</h2>
      <div class="w-full max-w-md">      <div class="mb-8">
       <h1 class="text-3xl font-bold mb-2" id="page-title">Login to Your Account</h1>
       <p class="opacity-80" id="page-subtitle">Welcome back! Please enter your details</p>
      </div>      
              <div id="error-message" 
            class="
                <?php echo empty($login_message) ? 'hidden' : 'bg-red-100 border-red-400 text-red-700' ?> 
                mb-4 p-3 rounded-lg border
            "
        >
       <p class="text-sm font-medium" id="error-text">
            <?php echo $login_message; ?>
        </p>
      </div>
              <form action="login.php" method="POST" id="login-form"></form>
      <p class="text-lg opacity-90" id="promo-text"></p>
     </div><!-- Background decoration -->
     <div class="absolute top-0 left-0 w-full h-full opacity-10">
      <div class="absolute top-20 left-20 w-32 h-32 rounded-full" id="bg-circle1"></div>
      <div class="absolute bottom-20 right-20 w-40 h-40 rounded-full" id="bg-circle2"></div>
     </div>
    </div>
   </div>
  </div>
  <script>
        // ---- CONFIG & DEFAULTS ----
        const defaultConfig = {
            // Colors (max 5)
            background_color: "#ffffff",           // BACKGROUND (left panel)
            surface_color: "#6366f1",              // SECONDARY_SURFACE (right panel)
            text_color: "#1f2937",                 // TEXT
            primary_action_color: "#6366f1",       // PRIMARY_ACTION (login button)
            secondary_action_color: "#e5e7eb",     // SECONDARY_ACTION (borders, inputs)
            
            // Typography
            font_family: "Inter",
            font_size: 16,
            
            // Text content
            page_title: "Login to Your Account",
            page_subtitle: "Welcome back! Please enter your details",
            email_label: "Email Address",
            password_label: "Password",
            login_button_text: "Sign In",
            forgot_password_text: "Forgot Password?",
            register_prompt: "Don't have an account?",
            register_link_text: "Sign Up",
            footer_text: "© 2024 Your Shop. All rights reserved."
        };
        
        // ---- DOM REFERENCES ----
        const leftPanel = document.getElementById('left-panel');
        const rightPanel = document.getElementById('right-panel');
        const logoIcon = document.getElementById('logo-icon');
        const brandName = document.getElementById('brand-name');
        const pageTitle = document.getElementById('page-title');
        const pageSubtitle = document.getElementById('page-subtitle');
        const emailLabelEl = document.getElementById('email-label-el');
        const passwordLabelEl = document.getElementById('password-label-el');
        const loginButton = document.getElementById('login-button');
        const loginButtonText = document.getElementById('login-button-text');
        const forgotLink = document.getElementById('forgot-link');
        const registerPrompt = document.getElementById('register-prompt');
        const registerLink = document.getElementById('register-link');
        const footerText = document.getElementById('footer-text');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const rememberCheckbox = document.getElementById('remember');
        const divider = document.getElementById('divider');
        const dividerText = document.getElementById('divider-text');
        const googleBtn = document.getElementById('google-btn');
        const facebookBtn = document.getElementById('facebook-btn');
        const promoTitle = document.getElementById('promo-title');
        const promoText = document.getElementById('promo-text');
        
        // SVG elements
        const bag1 = document.getElementById('bag1');
        const bag1Top = document.getElementById('bag1-top');
        const bag2 = document.getElementById('bag2');
        const bag2Top = document.getElementById('bag2-top');
        const circle1 = document.getElementById('circle1');
        const circle2 = document.getElementById('circle2');
        const circle3 = document.getElementById('circle3');
        const bgCircle1 = document.getElementById('bg-circle1');
        const bgCircle2 = document.getElementById('bg-circle2');
        
        // ---- CONFIG-DRIVEN RENDER ----
        async function onConfigChange(config) {
            const cfg = { ...defaultConfig, ...config };
            
            // Colors
            const bg = cfg.background_color || defaultConfig.background_color;
            const surface = cfg.surface_color || defaultConfig.surface_color;
            const textColor = cfg.text_color || defaultConfig.text_color;
            const primary = cfg.primary_action_color || defaultConfig.primary_action_color;
            const secondary = cfg.secondary_action_color || defaultConfig.secondary_action_color;
            
            // Font
            const fontFamily = cfg.font_family || defaultConfig.font_family;
            const baseSize = Number(cfg.font_size) || defaultConfig.font_size;
            const fontStack = `${fontFamily}, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`;
            
            // Apply background & text
            document.body.style.fontFamily = fontStack;
            leftPanel.style.backgroundColor = bg;
            leftPanel.style.color = textColor;
            rightPanel.style.backgroundColor = surface;
            rightPanel.style.color = "#ffffff";
            
            // Logo & brand
            logoIcon.style.color = primary;
            brandName.style.color = textColor;
            brandName.style.fontSize = baseSize * 1.5 + "px";
            
            // Headings
            pageTitle.style.color = textColor;
            pageTitle.style.fontSize = baseSize * 1.875 + "px";
            pageSubtitle.style.color = textColor;
            pageSubtitle.style.fontSize = baseSize + "px";
            
            // Form labels
            emailLabelEl.style.color = textColor;
            emailLabelEl.style.fontSize = baseSize * 0.875 + "px";
            passwordLabelEl.style.color = textColor;
            passwordLabelEl.style.fontSize = baseSize * 0.875 + "px";
            
            // Inputs
            emailInput.style.backgroundColor = bg;
            emailInput.style.color = textColor;
            emailInput.style.borderColor = secondary;
            emailInput.style.fontSize = baseSize + "px";
            
            passwordInput.style.backgroundColor = bg;
            passwordInput.style.color = textColor;
            passwordInput.style.borderColor = secondary;
            passwordInput.style.fontSize = baseSize + "px";
            
            // Input focus
            emailInput.onfocus = function() { this.style.borderColor = primary; };
            emailInput.onblur = function() { this.style.borderColor = secondary; };
            passwordInput.onfocus = function() { this.style.borderColor = primary; };
            passwordInput.onblur = function() { this.style.borderColor = secondary; };
            
            // Checkbox
            rememberCheckbox.style.accentColor = primary;
            
            // Login button
            loginButton.style.backgroundColor = primary;
            loginButton.style.color = "#ffffff";
            loginButton.style.fontSize = baseSize + "px";
            loginButton.onmouseenter = function() {
                this.style.backgroundColor = shadeColor(primary, -10);
            };
            loginButton.onmouseleave = function() {
                this.style.backgroundColor = primary;
            };
            
            // Links
            forgotLink.style.color = primary;
            forgotLink.style.fontSize = baseSize * 0.875 + "px";
            registerPrompt.style.color = textColor;
            registerPrompt.style.fontSize = baseSize * 0.875 + "px";
            registerLink.style.color = primary;
            registerLink.style.fontSize = baseSize * 0.875 + "px";
            
            // Divider
            divider.style.borderColor = secondary;
            dividerText.style.backgroundColor = bg;
            dividerText.style.color = textColor;
            dividerText.style.fontSize = baseSize * 0.875 + "px";
            
            // Social buttons
            googleBtn.style.backgroundColor = bg;
            googleBtn.style.borderColor = secondary;
            googleBtn.style.color = textColor;
            googleBtn.style.fontSize = baseSize * 0.875 + "px";
            
            facebookBtn.style.backgroundColor = bg;
            facebookBtn.style.borderColor = secondary;
            facebookBtn.style.color = textColor;
            facebookBtn.style.fontSize = baseSize * 0.875 + "px";
            
            // Footer
            footerText.style.color = textColor;
            footerText.style.fontSize = baseSize * 0.75 + "px";
            
            // Right panel promo
            promoTitle.style.fontSize = baseSize * 2.25 + "px";
            promoText.style.fontSize = baseSize * 1.125 + "px";
            
            // SVG shopping bags
            const lightSurface = lightenColor(surface, 20);
            const darkSurface = shadeColor(surface, -15);
            
            bag1.setAttribute('fill', lightSurface);
            bag1Top.setAttribute('fill', darkSurface);
            bag2.setAttribute('fill', lightSurface);
            bag2Top.setAttribute('fill', darkSurface);
            
            circle1.setAttribute('fill', primary);
            circle2.setAttribute('fill', primary);
            circle3.setAttribute('fill', "#ffffff");
            
            bgCircle1.style.backgroundColor = primary;
            bgCircle2.style.backgroundColor = primary;
            
            // Text content
            pageTitle.textContent = cfg.page_title || defaultConfig.page_title;
            pageSubtitle.textContent = cfg.page_subtitle || defaultConfig.page_subtitle;
            emailLabelEl.textContent = cfg.email_label || defaultConfig.email_label;
            passwordLabelEl.textContent = cfg.password_label || defaultConfig.password_label;
            loginButtonText.textContent = cfg.login_button_text || defaultConfig.login_button_text;
            forgotLink.textContent = cfg.forgot_password_text || defaultConfig.forgot_password_text;
            registerPrompt.textContent = cfg.register_prompt || defaultConfig.register_prompt;
            registerLink.textContent = cfg.register_link_text || defaultConfig.register_link_text;
            footerText.textContent = cfg.footer_text || defaultConfig.footer_text;
        }
        
        // Color utilities
        function shadeColor(color, percent) {
            const f = parseInt(color.slice(1), 16);
            const t = percent < 0 ? 0 : 255;
            const p = Math.abs(percent) / 100;
            const R = f >> 16;
            const G = (f >> 8) & 0x00ff;
            const B = f & 0x0000ff;
            const newR = Math.round((t - R) * p + R);
            const newG = Math.round((t - G) * p + G);
            const newB = Math.round((t - B) * p + B);
            return "#" + (0x1000000 + (newR << 16) + (newG << 8) + newB).toString(16).slice(1);
        }
        
        function lightenColor(color, percent) {
            return shadeColor(color, percent);
        }
        
        // ---- CAPABILITIES ----
        function buildCapabilities(config) {
            const cfg = { ...defaultConfig, ...config };
            
            const recolorables = [
                {
                    get: () => cfg.background_color || defaultConfig.background_color,
                    set: (value) => {
                        if (!window.elementSdk) return;
                        window.elementSdk.setConfig({ background_color: value });
                    }
                },
                {
                    get: () => cfg.surface_color || defaultConfig.surface_color,
                    set: (value) => {
                        if (!window.elementSdk) return;
                        window.elementSdk.setConfig({ surface_color: value });
                    }
                },
                {
                    get: () => cfg.text_color || defaultConfig.text_color,
                    set: (value) => {
                        if (!window.elementSdk) return;
                        window.elementSdk.setConfig({ text_color: value });
                    }
                },
                {
                    get: () => cfg.primary_action_color || defaultConfig.primary_action_color,
                    set: (value) => {
                        if (!window.elementSdk) return;
                        window.elementSdk.setConfig({ primary_action_color: value });
                    }
                },
                {
                    get: () => cfg.secondary_action_color || defaultConfig.secondary_action_color,
                    set: (value) => {
                        if (!window.elementSdk) return;
                        window.elementSdk.setConfig({ secondary_action_color: value });
                    }
                }
            ];
            
            const fontEditable = {
                get: () => cfg.font_family || defaultConfig.font_family,
                set: (value) => {
                    if (!window.elementSdk) return;
                    window.elementSdk.setConfig({ font_family: value });
                }
            };
            
            const fontSizeable = {
                get: () => cfg.font_size || defaultConfig.font_size,
                set: (value) => {
                    if (!window.elementSdk) return;
                    const numeric = typeof value === 'number' ? value : parseFloat(value) || defaultConfig.font_size;
                    window.elementSdk.setConfig({ font_size: numeric });
                }
            };
            
            return {
                recolorables,
                borderables: [],
                fontEditable,
                fontSizeable
            };
        }
        
        // ---- MAP TO EDIT PANEL VALUES ----
        function mapToEditPanelValues(config) {
            const cfg = { ...defaultConfig, ...config };
            return new Map([
                ['page_title', cfg.page_title || defaultConfig.page_title],
                ['page_subtitle', cfg.page_subtitle || defaultConfig.page_subtitle],
                ['email_label', cfg.email_label || defaultConfig.email_label],
                ['password_label', cfg.password_label || defaultConfig.password_label],
                ['login_button_text', cfg.login_button_text || defaultConfig.login_button_text],
                ['forgot_password_text', cfg.forgot_password_text || defaultConfig.forgot_password_text],
                ['register_prompt', cfg.register_prompt || defaultConfig.register_prompt],
                ['register_link_text', cfg.register_link_text || defaultConfig.register_link_text],
                ['footer_text', cfg.footer_text || defaultConfig.footer_text]
            ]);
        }
        
        // ---- INIT ELEMENT SDK ----
        (function initElementSdk() {
            if (!window.elementSdk) {
                onConfigChange(defaultConfig);
                return;
            }
            
            window.elementSdk.init({
                defaultConfig,
                onConfigChange: async (config) => {
                    await onConfigChange(config);
                },
                mapToCapabilities: (config) => buildCapabilities(config),
                mapToEditPanelValues
            });
        })();
    </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a8d089286ab4dae',t:'MTc2NDg2OTQ0NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>