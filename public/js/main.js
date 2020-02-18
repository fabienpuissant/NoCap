

  function onClickBtnLike(event){
    event.preventDefault();

    const url = this.href;
    const spanCount = this.querySelector('span.js-like');
    const icone = this.querySelector('img.js-img');
    axios.get(url).then(function(response){
      spanCount.textContent = response.data.likes;
      console.log(response.data);
      if(icone.src == 'http://localhost:8000/img/coeur.png'){
        console.log(icone.src);
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

        console.log(categorie);
        
        $.post('../model/script_query.php', {
            categorie : categorie
        }).done(function(data, textStatus, jqXHR){

            //Effacement de l'ancienne table
            var noeud = document.getElementById('photos');
            while(noeud.firstChild){
                noeud.removeChild(noeud.firstChild);
            }
            
            noeud = document.getElementById('noResults');
            while(noeud.firstChild){
                noeud.removeChild(noeud.firstChild);
            }
            //Affichage de la table
            
            var arrayTable = JSON.parse(data);
        });
    });
}

