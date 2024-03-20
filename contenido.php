<?php
/*
Plugin Name: ScanToolWP
Description: Plugin para mostrar información útil en el Dashboard de WordPress.
Version: 1.0
Author: Pedro Pablo Rodriguez
*/

// Agregar el menú en el Dashboard
add_action('admin_menu', 'scantoolwp_add_menu');

function scantoolwp_add_menu() {
    add_menu_page('ScanToolWP', 'ScanToolWP', 'manage_options', 'scantoolwp_dashboard', 'scantoolwp_dashboard_page');
    add_submenu_page('scantoolwp_dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'scantoolwp_dashboard', 'scantoolwp_dashboard_page');
    add_submenu_page('scantoolwp_dashboard', 'About', 'About', 'manage_options', 'scantoolwp_about', 'scantoolwp_about_page');
}

// Contenido de la página Dashboard
function scantoolwp_dashboard_page() {
    // Obtiene la información necesaria
    $theme = wp_get_theme();
    $active_plugins = get_option('active_plugins');
    $all_plugins = get_plugins();
    $pages_count = wp_count_posts('page')->publish;
    $blogs_count = wp_count_posts('post')->publish;

    // Imprime la información
    echo "<h1>Dashboard</h1>";
    echo "<p>Nombre del sitio: " . get_bloginfo('name') . "</p>";
    echo "<p>Url de instalación: " . site_url() . "</p>";
    echo "<p>Url de WordPress: " . admin_url() . "</p>";
    echo "<p>Versión de WordPress: " . get_bloginfo('version') . "</p>";
    echo "<p>Listado de temas instalados:</p>";
    echo "<ul>";
    foreach (wp_get_themes() as $installed_theme) {
        if ($installed_theme->stylesheet === $theme->stylesheet) {
            echo "<li><strong>{$installed_theme->name}</strong></li>";
        } else {
            echo "<li>{$installed_theme->name}</li>";
        }
    }
    echo "</ul>";
    echo "<p>Listado de plugins instalados:</p>";
    echo "<ul>";
    foreach ($all_plugins as $plugin_file => $plugin_data) {
        $status = in_array($plugin_file, $active_plugins) ? 'green' : 'red';
        echo "<li style='color:$status;'>{$plugin_data['Name']}</li>";
    }
    echo "</ul>";
    echo "<p>Número de páginas publicadas: $pages_count</p>";
    echo "<p>Número de blogs publicados: $blogs_count</p>";
}

// Contenido de la página About
function scantoolwp_about_page() {
    echo "<h1>About</h1>";
    echo "<p>Nombre del autor del plugin: Tu Nombre</p>";
    echo "<a href='https://www.facebook.com/nativapps' class='button-primary' target='_blank'>Facebook</a>";
    echo "<a href='https://www.instagram.com/nativapps/' class='button-primary' target='_blank'>Instagram</a>";
    echo "<a href='https://www.linkedin.com/company/nativapps-inc/' class='button-primary' target='_blank'>LinkedIn</a>";
}