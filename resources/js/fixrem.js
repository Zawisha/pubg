function fixRem() {
    let ww = $(window).width();
    if (ww < 620) {
        $('html').css('font-size', (ww /  32) + 'px');
        // $('html').css('font-size', '3.33px');
    } else {
        $('html').css('font-size', (ww / 192) + 'px');
    }
}

$(window).resize(fixRem);
fixRem();