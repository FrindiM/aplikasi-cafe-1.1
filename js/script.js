function bukaTampilan() {
    $(".section").css("display", "block");
    // Gulir ke elemen dengan id 'tampilan'
    $(".section")[0].scrollIntoView();
}


$(document).ready(function () {
    // console.log("haii");
    $(".btnBuka").click(function (e) {
        e.preventDefault();
        // console.log('masuk pak eko');
        bukaTampilan()
    });
});