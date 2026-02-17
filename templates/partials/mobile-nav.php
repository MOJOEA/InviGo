<?php
?>
<nav class="md:hidden fixed bottom-0 w-full bg-white border-t-2 border-black p-2 flex justify-around rounded-t-xl z-50">
    <a href="/explore" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'explore' ? 'text-black' : 'text-gray-400' ?>">
        <span class="material-symbols-outlined">search</span>
        <span class="text-[10px] font-bold">ค้นหา</span>
    </a>
    <a href="/events/create" class="bg-[#FFE600] border-2 border-black rounded-full p-3 -mt-8 shadow-[2px_2px_0px_0px_black]">
        <span class="material-symbols-outlined">add</span>
    </a>
    <a href="/my-events" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'my-events' ? 'text-black' : 'text-gray-400' ?>">
        <span class="material-symbols-outlined">calendar_month</span>
        <span class="text-[10px] font-bold">กิจกรรม</span>
    </a>
</nav>
