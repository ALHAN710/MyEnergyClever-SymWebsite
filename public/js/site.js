$('#add-smartMod').click(function(){
    //Je récupère le numéro du futur champ que je vais créer
    const index = +$('#widgets-count').val();
    console.log(index);
    $('#widgets-count').val(index+1);

    //Je récupère le prototype des entrées(champs) et je remplace dans ce
    //prototype toutes les expressions régulières (drapeau g) "___name___" (/___name___/) par l'index
    const tmpl = $('#site_smartMods').data('prototype').replace(/__name__/g,index);
    console.log(tmpl);

    //J'ajoute à la suite de la div contenant le sous-formulaire ce code
    $('#site_smartMods').append(tmpl);
    handleDeleteButton();
});
handleDeleteButton();
updateCounter();

function updateCounter(){
    const count = +$('#site_smartMods div.form-group').length;

    $('#widgets-count').val(count);
}

function handleDeleteButton(){
    //Je gère l'action du click sur les boutton possédant l'attribut data-action = "delete"
    $('button[data-action="delete"]').click(function(){
        //Je récupère l'identifiant de la cible(target) à supprimer en recherchant 
        //dans les attributs data-[quelque chose](grâce à dataset) celui dont quelque chose = target (grâce à target)
        const target = this.dataset.target;
        console.log(target);
        $(target).remove();
    });

    
};