function selectCategorie() {

    $(function () {

        var categorie = $('#Categorie').val();
        var url = "http://nocap.ddns.net:8000/classement/" + categorie;
        window.location = url;

    });
}