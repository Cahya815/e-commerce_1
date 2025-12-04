<!--?php
session_start();

// ----------------------------------------------------
// LOGIKA OTORISASI DAN LOGOUT HARUS DI ATAS SEMUA OUTPUT
// ----------------------------------------------------

// 1. Pengecekan Otorisasi: Jika sesi tidak lengkap, redirect ke login
if (!isset($_SESSION["is_login"]) || $_SESSION["is_login"] !== true) {
    header("Location: login.php");
    exit();
}

// 2. Logika Logout
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
// ----------------------------------------------------
?-->
<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
        body {
            box-sizing: border-box;
            background-color: #f3f4f6;
        }
    </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="/_sdk/element_sdk.js" type="text/javascript"></script>
 </head>
 <body class="min-h-screen"><!--?php include "header.php" ?-->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"><!-- Welcome Header -->
   <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600 mt-2">Selamat datang kembali, <!--?= $_SESSION["user_name"] ?-->!</p>
   </div>
   <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"><!-- Profile Card -->
    <div class="lg:col-span-1">
     <div class="bg-white rounded-lg shadow-md p-6">
      <div class="text-center"><!-- Avatar -->
       <div class="w-24 h-24 bg-indigo-600 rounded-full mx-auto flex items-center justify-center mb-4"><span class="text-white text-3xl font-bold"> <!--?= strtoupper(substr($_SESSION["user_name"], 0, 1)) ?--> </span>
       </div>
       <h2 class="text-xl font-bold text-gray-900 mb-1"><!--?= $_SESSION["user_name"] ?--></h2>
       <p class="text-sm text-gray-600 mb-4"><!--?= $_SESSION["user_email"] ?--></p><!-- Role Badge --> 
      </div>
      <hr class="my-6"><!-- Account Info -->
      <div class="space-y-3">
       <div>
        <p class="text-xs text-gray-500 uppercase tracking-wide">User ID</p>
        <p class="text-sm font-medium text-gray-900">#<!--?= $_SESSION["user_id"] ?--></p>
       </div>
       <div>
        <p class="text-xs text-gray-500 uppercase tracking-wide">Status Akun</p>
        <p class="text-sm font-medium text-green-600">● Aktif</p>
       </div>
       <div>
        <p class="text-xs text-gray-500 uppercase tracking-wide">Bergabung Sejak</p>
        <p class="text-sm font-medium text-gray-900"><!--?= date('d M Y') ?--></p>
       </div>
      </div>
      <hr class="my-6"><!-- Logout Button -->
      <form action="dashboard.php" method="POST"><button type="submit" name="logout" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors"> Logout </button>
      </form>
     </div>
    </div><!-- Main Content -->
    <div class="lg:col-span-2 space-y-6"><!-- Stats Cards -->
     <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"><!-- Total Orders -->
      <div class="bg-white rounded-lg shadow-md p-6">
       <div class="flex items-center justify-between">
        <div>
         <p class="text-sm text-gray-600 mb-1">Total Pesanan</p>
         <p class="text-2xl font-bold text-gray-900">0</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
         <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
         </svg>
        </div>
       </div>
      </div><!-- Total Spending -->
      <div class="bg-white rounded-lg shadow-md p-6">
       <div class="flex items-center justify-between">
        <div>
         <p class="text-sm text-gray-600 mb-1">Total Belanja</p>
         <p class="text-2xl font-bold text-gray-900">Rp 0</p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
         <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
         </svg>
        </div>
       </div>
      </div>
     </div><!-- Quick Actions -->
     <div class="bg-white rounded-lg shadow-md p-6">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4"><a href="#" class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-2">
         <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
         </svg>
        </div><span class="text-sm font-medium text-gray-700">Belanja</span> </a> <a href="#" class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-2">
         <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
         </svg>
        </div><span class="text-sm font-medium text-gray-700">Pesanan</span> </a> <a href="#" class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-2">
         <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
         </svg>
        </div><span class="text-sm font-medium text-gray-700">Wishlist</span> </a> <a href="#" class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-2">
         <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
         </svg>
        </div><span class="text-sm font-medium text-gray-700">Pengaturan</span> </a>
      </div>
     </div><!-- Recent Activity -->
     <div class="bg-white rounded-lg shadow-md p-6">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Aktivitas Terbaru</h3>
      <div class="text-center py-8">
       <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
       </svg>
       <p class="text-gray-500">Belum ada aktivitas</p>
       <p class="text-sm text-gray-400 mt-1">Mulai berbelanja untuk melihat aktivitas Anda</p>
      </div>
     </div>
    </div>
   </div>
  </div>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9a8d3a2882492ce1',t:'MTc2NDg3MTQ3Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>