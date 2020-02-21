

function onClickBtnLike(event){
  event.preventDefault();

  const url = this.href;
  const spanCount = this.querySelector('span.js-like');
  const icone = this.querySelector('img.js-img');
  axios.get(url).then(function(response){
    spanCount.textContent = response.data.likes;
    if(icone.src == 'http://localhost:8000/img/coeur.png'){
      icone.src = 'http://localhost:8000/img/coeurliked.png';
    } else {
      icone.src = 'http://localhost:8000/img/coeur.png';
    }
  })
}

document.querySelectorAll('a.js-like').forEach(function(link){
  link.addEventListener('click', onClickBtnLike);
});

function selectCategorie(){

  $(function(){

      var categorie = $('#Categorie').val();
      var url = "http://localhost:8000/flux/" + categorie;
      window.location = url;
      
  });
}

  $(document).ready(function(){

    var nbPhotoDisplay = 2;
    var categorie = $('#Categorie').val();

    $(window).on("scroll", function() {
      var scrollHeight = $(document).height();
      var scrollPosition = $(window).height() + $(window).scrollTop();
      if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
      
        axios.get("http://localhost:8000/flux/"+categorie+"/"+nbPhotoDisplay).then(function(response){
        
        //response = array of photos to display
        //var photoArray = JSON.parse(response.data.photos[0]);
        var photoArray = response.data.photos;
          for(var photo in photoArray){
          console.log(photoArray[photo]);
          $('#end').last().append(
            "<div class = 'container mt-4'>"+
          "<div class='card'>"+
          "<div class='card-body'>"+
            "<h5 class='card-title'>"+ photoArray[photo]['Title'] +"</h5>" + 
            "<p class='card-text'>"+ photoArray[photo]['Description'] + "</p>"+
            "<p class='card-text'><small>Auteur :" + photoArray[photo]['Author'] + "</small></p>"+
          "</div>"+
          "<img src=http://localhost:8000/photos/"+ photoArray[photo]['FileName'] + " class='card-img-top photo_actu' alt='image_displayed'>"+
          "<div class='vote'  data-ref_id = " + photoArray[photo]["id"] + "  id = "+photoArray[photo]['Auteur']+">"+
            "<div class='vote_btns'>"+
              "<div class='vote_btn vote_like' data-vote = 'like'>"+
                  "<a href = http://localhost:8000/flux/"+photoArray[photo]['id']+"/like" + " class = 'js-like' style='text-decoration:none;color:black'>"+
                    "<img src=http://localhost:8000/img/"+ photoArray[photo]['Like'] +  " alt='like icon' class = 'js-img' onclick='onClickBtnLike()'><span class = 'js-like'>" + photoArray[photo]['Likes'] + "</span>"+
                  "</a>"+
              "</div>"+
            "</div>"+
          "</div>"+
          "</div>"+
        "</div>"+
        "</div>"+
        "<br/>");
        }
          
      });
      document.querySelectorAll('a.js-like').forEach(function(link){
        link.addEventListener('click', onClickBtnLike);
      });
      nbPhotoDisplay++;
    }
  });

});

