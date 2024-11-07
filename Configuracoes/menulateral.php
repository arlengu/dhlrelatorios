<div class="sidebar" id="sidebar">
<div class="sidebar-inner slimscroll">
<div id="sidebar-menu" class="sidebar-menu">
<ul>
<li>
<a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i><span> Dashboard</span> </a>
</li>
<li class="submenu">
<a href="javascript:void(0);"><i class="fa-solid fa-truck-ramp-box"></i></i><span> Inbound</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="lista.php"> Relatórios</a></li>
<li><a href="inbound.php"> Recebimento</a></li>
<li><a href="consulta.php"> Consultar</a></li>
</div>
</div>
</div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    var currentUrl = window.location.href;
    var menuItems = document.querySelectorAll('#sidebar-menu a');

    menuItems.forEach(function (item) {
        if (item.href === currentUrl) {
            // Remove a classe 'active' de todos os itens
            menuItems.forEach(function (i) {
                i.parentElement.classList.remove('active');
            });

            // Adiciona a classe 'active' ao item principal
            item.parentElement.classList.add('active');

            // Ajusta a cor dos itens do submenu
            var submenuItems = item.closest('ul').querySelectorAll('li a');
            submenuItems.forEach(function (subItem) {
                if (subItem.href === currentUrl) {
                    subItem.classList.add('active');
                } else {
                    subItem.classList.remove('active');
                }
            });
        } else {
            item.parentElement.classList.remove('active');
        }
    });
});
</script>



