<?php 

// Key Value From Json
function kvfj($json, $key) {
    if ($json == null):
        return null;
    else:
        $json = $json;
        $json = json_decode($json, true);
        if (array_key_exists($key, $json)):
            return $json[$key];
        else:
            return null;
        endif;
    endif;
}

function getModulesArray() {
    $a = [
        '0' => 'Productos',
        '1' => 'Blog'
    ];

    return $a;
}

function getRoleUserArray($mode, $id) {
    $roles = ['0' => 'Usuario', '1' => 'Administrador'];
    if (!is_null($mode)) {
        return $roles;
    }
    else {
        return $roles[$id];
    }
}

function getUserStatusArray($mode, $id) {
    $status = ['0' => 'Registrado', '1' => 'Verificado', '100' => 'Baneado'];
    if (!is_null($mode)) {
        return $status;
    }
    else {
        return $status[$id];
    }
    
}

function user_permissions() {
    $p = [
        'dashboard' => [
            'icon' => '<i class="fas fa-boxes"></i>',
            'title' => 'Módulo Dashboard',
            'keys' => [
                'dashboard' => 'Puede ver el dashboard.',
                'dashboard_small_stats' => 'Puede ver las estadisticas rápidas.',
                'dashboard_sell_today' => 'Puede ver lo facturado hoy.',
            ]
        ],
        'products' => [
            'icon' => '<i class="fas fa-home"></i>',
            'title' => 'Módulo de Productos',
            'keys' => [
                'products' => 'Puede ver el listado de productos.',
                'product_add' => 'Puede agregar nuevos productos.',
                'product_edit' => 'Puede editar productos.',
                'product_search' => 'Puede buscar productos.',
                'product_delete' => 'Puede eliminar productos.',
                'product_gallery_add' => 'Puede agregar imagenes a la galeria.',
                'product_gallery_delete' => 'Puede eliminar imagenes de la galeria.'
            ]
        ],
        'categories' => [
            'icon' => '<i class="fas fa-folder-open"></i>',
            'title' => 'Módulo de Categorias',
            'keys' => [
                'categories' => 'Puede ver el listado de categorias.',
                'category_add' => 'Puede añadir nuevas categorias.',
                'category_edit' => 'Puede editar categorias.',
                'category_delete' => 'Puede eliminar categorias.'
            ]
        ],
    ];

    return $p;
}