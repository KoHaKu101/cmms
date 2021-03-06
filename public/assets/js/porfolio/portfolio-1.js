(function($, window, document, undefined) {
    'use strict';

    $('#js-grid-juicy-projects').cubeportfolio({
        filters: '.js-filters-juicy-projects',
        loadMore: '#js-loadMore-juicy-projects',
        loadMoreAction: 'click',
        plugins: {
            loadMore: {
                loadItems: 8,
            }
        },
        layoutMode: 'grid',
        defaultFilter: '*',
        animationType: 'quicksand',
        gapHorizontal: 0,
        gapVertical: 0,
        gridAdjustment: 'responsive',
        mediaQueries: [{
            width: 800,
            cols: 4
        }, {
            width: 800,
            cols: 3
        }, {
            width: 800,
            cols: 2
        }, {
            width: 800,
            cols: 1
        }],
        caption: 'overlayBottomReveal',
        displayType: 'sequentially',
        displayTypeSpeed: 80,

        // lightbox
        // lightboxDelegate: '.cbp-lightbox',
        lightboxGallery: true,
        lightboxTitleSrc: 'data-title',
        lightboxCounter: '<div class="cbp-popup-lightbox-counter">{{current}} of {{total}}</div>',

        // // singlePage popup
        // singlePageDelegate: '.cbp-singlePage',
        // singlePageDeeplinking: true,
        // singlePageStickyNavigation: false,
        // singlePageCounter: '<div class="cbp-popup-singlePage-counter">{{current}} of {{total}}</div>',
        // singlePageCallback: function(url, element) {
        //     // to update singlePage content use the following method: this.updateSinglePage(yourContent)
        //     var t = this;
        //
        //     $.ajax({
        //             url: url,
        //             type: 'GET',
        //             dataType: 'html',
        //             timeout: 30000
        //         })
        //         .done(function(result) {
        //             t.updateSinglePage(result);
        //         })
        //         .fail(function() {
        //             t.updateSinglePage('AJAX Error! Please refresh the page!');
        //         });
        // },
    });

})(jQuery, window, document);
