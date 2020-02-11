plugin.tx_pictures_pictures {
    view {
        templateRootPaths.0 = EXT:{extension.extensionKey}/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_pictures_pictures.view.templateRootPath}
        partialRootPaths.0 = EXT:pictures/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_pictures_pictures.view.partialRootPath}
        layoutRootPaths.0 = EXT:pictures/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_pictures_pictures.view.layoutRootPath}
    }

    persistence {
        storagePid = {$plugin.tx_pictures_pictures.persistence.storagePid}
        #recursive = 1
    }

    features {
        #skipDefaultArguments = 1
        # if set to 1, the enable fields are ignored in BE context
        ignoreAllEnableFieldsInBe = 0
        # Should be on by default, but can be disabled if all action in the plugin are uncached
        requireCHashArgumentForActionArguments = 1
    }

    mvc {
        #callDefaultActionIfActionCantBeResolved = 1
    }
}

page.includeJSFooter {
    fancybox = EXT:pictures/Resources/Public/Library/fancybox/jquery.fancybox.min.js
    pictures-main = EXT:pictures/Resources/Public/JavaScripts/main.js
}
