$(function () {
    //Add prod
    $('.js-add-item').click(function (e) {
        let prodId = e.target.id;
        let quantity = $('.js-quantity-'+prodId).val();
        let url = "{{ path('app_add_item', {'prodId': 'prodId', 'quantity': 'quantity' })}}";
        url = url.replace("prodId", parseInt(prodId)).replace("quantity", quantity === "" ? 1 : quantity);
        $(this).attr('href', url);
    });
    //Remove prod
    $('.js-remove-item').click(function (e) {
        let prodId = e.target.id.replace('del_prod_', '');
        let quantity = $('.js-quantity-'+prodId).val()
        let url = "{{ path('app_remove_item', {'prodId': 'prodId', 'quantity': 'quantity' })}}";
        url = url.replace("prodId", parseInt(prodId)).replace("quantity", quantity === "" ? 1 : quantity);
        $(this).attr('href', url);
    });
});