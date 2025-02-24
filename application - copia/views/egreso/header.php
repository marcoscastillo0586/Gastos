    <link rel="stylesheet" href="<?=base_url()?>assets/vendor/smartwizard/css/smart_wizard_all.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<style>
    /* Estilos generales para los encabezados */
    h1, h4, h6 { 
        text-align: center; 
    }
    h4 {
        color: #000;
    }

    /* Estilos de las pestañas (nav-tabs) */
    .nav-tabs {
        border-bottom: 1px solid #ddd;
        padding-left: 15px;
    }

    .nav-tabs > li {
        float: left;
        margin-bottom: -1px;
    }

    .nav-tabs > li > a {
        margin-right: 2px;
        line-height: 1.42857143;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
        padding-right: 15px;
        padding-left: 15px;
    }

    .nav-tabs > li > a:hover {
        border-color: #eee #eee #ddd;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        color: #555;
        cursor: default;
        background-color: #fff;
        border: 1px solid #ddd;
        border-bottom-color: transparent;
    }

    /* Tabs justificados */
    .nav-tabs.nav-justified {
        width: 100%;
        border-bottom: 0;
    }

    .nav-tabs.nav-justified > li {
        float: none;
    }

    .nav-tabs.nav-justified > li > a {
        margin-bottom: 5px;
        text-align: center;
    }

    .nav-tabs.nav-justified > .active > a,
    .nav-tabs.nav-justified > .active > a:hover,
    .nav-tabs.nav-justified > .active > a:focus {
        border: 1px solid #ddd;
    }

    /* Tabs justificados en pantallas medianas */
    @media (min-width: 768px) {
        .nav-tabs.nav-justified > li {
            display: table-cell;
            width: 1%;
        }

        .nav-tabs.nav-justified > li > a {
            margin-bottom: 0;
            border-bottom: 1px solid #ddd;
            border-radius: 4px 4px 0 0;
        }

        .nav-tabs.nav-justified > .active > a,
        .nav-tabs.nav-justified > .active > a:hover,
        .nav-tabs.nav-justified > .active > a:focus {
            border-bottom-color: #fff;
        }
    }

    /* Estilo de las pestañas de contenido */
    .tab-content > .tab-pane {
        display: none;
    }

    .tab-content > .active {
        display: block;
    }

    /* Estilo para los menús desplegables en las pestañas */
    .nav-tabs .dropdown-menu {
        margin-top: -1px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    /* Efecto hover en las tarjetas */
    .card:hover {
        background-color: #3EA3DE;
        color: #fff;
    }

    /* Estilos personalizados para las tarjetas con imagen */
    .imgCard {
        background-color: #fff;
        margin: 5px;
        padding: 5px;
    }

    .imgCard-selected {
        border: 6px solid #099a9f;
        margin: 5px;
        padding: 5px;
    }

    /* Borde superior azul en el tab activo */
    li.active.li-tab {
    border-top: 3px solid blue;
    background-color: #f0f0f0;  /* Fondo gris claro */
    color: #333;  /* Color de texto */
    font-weight: bold; /* Texto en negrita */
}
#tabla-tiket {
  overflow-x: hidden !important;  /* Oculta el scroll horizontal */
  max-width: 100%;  /* Evita que el contenido se expanda más allá del contenedor */
}
</style>

