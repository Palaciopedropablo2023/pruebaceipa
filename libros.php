<?php
/*
Plugin Name: Libros Plugin
Description: Plugin para gestionar libros en WordPress.
*/

// Registrar Custom Post Type para Libros
function registrar_post_type_libros() {
    $labels = array(
        'name'               => 'Libros',
        'singular_name'      => 'Libro',
        'menu_name'          => 'Libros',
        'name_admin_bar'     => 'Libro',
        'add_new'            => 'Agregar Nuevo',
        'add_new_item'       => 'Agregar Nuevo Libro',
        'new_item'           => 'Nuevo Libro',
        'edit_item'          => 'Editar Libro',
        'view_item'          => 'Ver Libro',
        'all_items'          => 'Todos los Libros',
        'search_items'       => 'Buscar Libros',
        'parent_item_colon'  => 'Libros Padre:',
        'not_found'          => 'No se encontraron libros.',
        'not_found_in_trash' => 'No se encontraron libros en la papelera.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'libro' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' ),
    );

    register_post_type( 'libro', $args );
}
add_action( 'init', 'registrar_post_type_libros' );

// Agregar campos personalizados para Libros
function agregar_campos_personalizados_libros() {
    add_meta_box(
        'detalle_libro',
        'Detalles del Libro',
        'mostrar_formulario_detalle_libro',
        'libro',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'agregar_campos_personalizados_libros' );

// Mostrar formulario para campos personalizados de Libros
function mostrar_formulario_detalle_libro( $post ) {
    // Obtener valores guardados
    $genero = get_post_meta( $post->ID, 'genero', true );
    $autor = get_post_meta( $post->ID, 'autor', true );
    $anio_publicacion = get_post_meta( $post->ID, 'anio_publicacion', true );

    // Output del formulario
    ?>
    <label for="genero">Género:</label>
    <input type="text" id="genero" name="genero" value="<?php echo esc_attr( $genero ); ?>"><br>

    <label for="autor">Autor:</label>
    <input type="text" id="autor" name="autor" value="<?php echo esc_attr( $autor ); ?>"><br>

    <label for="anio_publicacion">Año de Publicación:</label>
    <input type="text" id="anio_publicacion" name="anio_publicacion" value="<?php echo esc_attr( $anio_publicacion ); ?>"><br>
    <?php
}

// Guardar valores de campos personalizados de Libros
function guardar_valores_campos_personalizados_libros( $post_id ) {
    if ( isset( $_POST['genero'] ) ) {
        update_post_meta( $post_id, 'genero', sanitize_text_field( $_POST['genero'] ) );
    }
    if ( isset( $_POST['autor'] ) ) {
        update_post_meta( $post_id, 'autor', sanitize_text_field( $_POST['autor'] ) );
    }
    if ( isset( $_POST['anio_publicacion'] ) ) {
        $anio_publicacion = intval( $_POST['anio_publicacion'] ); // Convertir a entero
        if ( $anio_publicacion >= 1000 && $anio_publicacion <= 9999 ) { // Validar el rango de años
            update_post_meta( $post_id, 'anio_publicacion', $anio_publicacion );
        } else {
            // Mostrar un mensaje de error o registrar un error en algún lugar
        }
    }
}
add_action( 'save_post', 'guardar_valores_campos_personalizados_libros' );

// Eliminar libro
function eliminar_libro( $post_id ) {
    // Verificar permisos y nonce
    if ( ! current_user_can( 'delete_post', $post_id ) ) {
        return;
    }

    // Eliminar metadatos asociados al libro
    delete_post_meta( $post_id, 'genero' );
    delete_post_meta( $post_id, 'autor' );
    delete_post_meta( $post_id, 'anio_publicacion' );
}
// Eliminar el metabox "Detalle del Libro"
function remover_metabox_detalle_libro() {
    remove_meta_box( 'detalle_libro', 'libro', 'normal' );
}
add_action( 'add_meta_boxes', 'remover_metabox_detalle_libro' );

// Agregar acción para eliminar libro
add_action( 'before_delete_post', 'eliminar_libro' );

// Crear página de listado de libros
function crear_pagina_listado_libros() {
    $pagina = get_page_by_title( 'Listado de Libros' );

    if ( ! $pagina ) {
        $nueva_pagina = array(
            'post_title'    => 'Listado de Libros',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
        );

        wp_insert_post( $nueva_pagina );
    }
}
register_activation_hook( __FILE__, 'crear_pagina_listado_libros' );