        </main>
    </div>
    <script>
    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('active');
        document.querySelector('.admin-main').classList.toggle('sidebar-active');
    }
    function confirmDelete(msg) { return confirm(msg || 'Are you sure you want to delete this item?'); }
    </script>
</body>
</html>
