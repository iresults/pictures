
plugin.tx_pictures_pictures {
    view {
        # cat=plugin.tx_pictures_pictures/file; type=string; label=Path to template root (FE)
        templateRootPath = EXT:pictures/Resources/Private/Templates/
        # cat=plugin.tx_pictures_pictures/file; type=string; label=Path to template partials (FE)
        partialRootPath = EXT:pictures/Resources/Private/Partials/
        # cat=plugin.tx_pictures_pictures/file; type=string; label=Path to template layouts (FE)
        layoutRootPath = EXT:pictures/Resources/Private/Layouts/
    }
    persistence {
        # cat=plugin.tx_pictures_pictures//a; type=string; label=Default storage PID
        storagePid =
    }
}
