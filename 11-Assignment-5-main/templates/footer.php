    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm">&copy; 2026 ระบบลงทะเบียนเรียน. All rights reserved.</p>
        </div>
    </footer>

    <!-- Custom Confirm Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full mx-4">
            <p id="confirmMessage" class="mb-4 text-gray-800"></p>
            <div class="flex justify-end space-x-2">
                <button id="confirmNo" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">ยกเลิก</button>
                <button id="confirmYes" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">ยืนยัน</button>
            </div>
        </div>
    </div>

    <script>
        function showConfirmModal(message, yesCallback) {
            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmYes').onclick = () => {
                yesCallback();
                document.getElementById('confirmModal').classList.add('hidden');
            };
            document.getElementById('confirmNo').onclick = () => {
                document.getElementById('confirmModal').classList.add('hidden');
            };
        }
    </script>
</body>
</html>
