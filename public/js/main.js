function disable() {
  alert('ok');
}

$(function () {
  $('.lazy').lazy();
});

function onClickBtnLike(event) {
  event.preventDefault();

  const url = this.href;
  const spanCount = this.querySelector('span.js-like');
  const icone = this.querySelector('.js-img');
  axios.get(url).then(function (response) {
    spanCount.textContent = response.data.likes;
    if (icone.src == 'http://nocap.ddns.net:8000/img/coeur.png') {
      icone.src = 'http://nocap.ddns.net:8000/img/coeurliked.png';
    } else {
      icone.src = 'http://nocap.ddns.net:8000/img/coeur.png';
    }
  })
}

document.querySelectorAll('a.js-like').forEach(function (link) {
  link.addEventListener('click', onClickBtnLike);
});

function selectCategorie() {

  $(function () {

    var categorie = $('#Categorie').val();
    var url = "http://nocap.ddns.net:8000/flux/" + categorie;
    window.location = url;

  });
}



$(document).ready(function () {

  var nbPhotoDisplay = 2;
  var categorie = $('#Categorie').val();
  var scroll_more = true;

  $(window).on("scroll", function () {
    if (scroll_more) {

      var scrollHeight = $(document).height();
      var scrollPosition = $(window).height() + $(window).scrollTop();
      if ((scrollHeight - scrollPosition) <= 1000) {
        axios.get("http://nocap.ddns.net:8000/flux/" + categorie + "/" + nbPhotoDisplay).then(function (response) {
          //axios.get("http://localhost:8000/flux/" + categorie + "/" + nbPhotoDisplay).then(function (response) {

          //response = array of photos to display
          //var photoArray = JSON.parse(response.data.photos[0]);
          var photoArray = response.data.photos;
          if (photoArray.length == 0) {
            scroll_more = false;
          }
          for (var photo in photoArray) {

            $('#end').last().append(
              //"<div class = 'container mt-4'>" +
              "<div class='card'>" +
              "<div class='card-body'>" +
              "<h5 class='card-title' style = 'font-size:4em;'>" + photoArray[photo]['Title'] + "</h5>" +
              "<p class='card-text' style = 'font-size:3em;'>" + photoArray[photo]['Description'] + "</p>" +
              "<p class='card-text' style = 'font-size:2.5em;'><small>Auteur : " + photoArray[photo]['Author'] + "</small></p>" +
              "</div>" +
              "<img src=" + photoArray[photo]['FileName'] + " class='card-img-top lazy' alt='image_displayed'>" +
              "<div class='vote'  data-ref_id = " + photoArray[photo]["id"] + "  id = " + photoArray[photo]['Auteur'] + ">" +
              "<div class='vote_btns'>" +
              "<div class='vote_btn vote_like' data-vote = 'like'>" +
              "<a href = '/flux/" + photoArray[photo]['id'] + "/like'" + " class = 'js-like'  style='text-decoration:none;color:black' onclick='onClickBtnLike()'>" +
              "<img src=" + photoArray[photo]['Like'] + " alt='like icon' class = 'js-img' /><span class = 'js-like ml-4 mt-1' style = 'font-size:2.5em;'>" + photoArray[photo]['Likes'] + "</span>" +
              "</a>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>" +
              //"</div>" +
              "<br/>");
          }

          document.querySelectorAll('a.js-like').forEach(function (link) {
            link.addEventListener('click', onClickBtnLike);
          });

        });


        nbPhotoDisplay++;
      }
    }
  });

});




