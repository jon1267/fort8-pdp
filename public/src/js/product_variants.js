// variants - DOM объект в к-рый вставляются/удаляются поля ввода вариантов
const variants = document.getElementById('add-variants-div');
const variantsTable = document.getElementById('variants-table');
const variantsTableDelBtn = document.querySelectorAll(".old_delete_button");

$(document).ready(function(){

    let variantsCount = document.getElementById('old-variants-count');
    variantsCount = (variantsCount == null) ? 0 : parseInt(variantsCount.value, 10);

    $('#add-variants-button').on('click', function (e) {
        e.preventDefault();
        addVariantsTable(variantsCount++);
    });

    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    // удаление уже имеющихся (уже сохраненных в мускуле)
    for (let i = 0; i < variantsTableDelBtn.length; i++) {
        variantsTableDelBtn[i].onclick = function(e){
            e.preventDefault();
            deleteVariantsDiv('old_variant_tr_' + i);
        };
    }

});

// удаляем див или <tr> по id (с набором вариаций из DOM)
function deleteVariantsDiv(idDiv) {
    confirmDeleteVariant(idDiv);
}

// удаляется элемнт из DOM
function confirmDeleteVariant(idDiv) {
    console.log(idDiv);
    event.preventDefault();
    Swal.fire({
        title: 'Вы уверены?',
        icon: 'warning',
        text: "Вы точно хотите это удалить?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!'
    }).then((result) => {
        //console.log(result.value);
        if(result.value) {
            document.getElementById(idDiv).remove();
        }
    })
}

// вставляем в таблицу в <tr> 5 инпутов и 2 чекбокса
function addVariantsTable(i){

    const item = document.createElement('tr');
    item.id = 'variant_tr_' + i;
    item.classList.add('text-center');

    item.innerHTML = `
    <td><input type="text" id="name_${i}" name="variants[${i}][name]" class="form-control"></td>
    <td><input type="text" id="volume_${i}" name="variants[${i}][volume]" class="form-control"></td>
    <td><input type="text" id="art_${i}" name="variants[${i}][art]" class="form-control"></td>
    <td><input type="text" id="price_ua_${i}" name="variants[${i}][price_ua]" class="form-control"></td>
    <td><input type="text" id="price_ru_${i}" name="variants[${i}][price_ru]" class="form-control"></td>
    <td><div class="custom-control custom-checkbox">
    <input type="checkbox" id="active_ua_${i}" name="variants[${i}][active_ua]" value="1" class="custom-control-input">
    <label for="active_ua_${i}" class="custom-control-label"></label></div></td>
    <td><div class="custom-control custom-checkbox">
    <input type="checkbox" id="active_ru_${i}" name="variants[${i}][active_ru]" value="1" class="custom-control-input">
    <label for="active_ru_${i}" class="custom-control-label"></label></div></td>
    <td><a href="#" id="delete_button_${i}" class="btn btn-danger btn-sm" title="удалить вариант" >&nbsp;X&nbsp;</a></td>
    `;

    variantsTable.appendChild(item);

    // удаление только что добавленных "новых" вариантов (инпуты с чеками в <tr>)
    const deleteButtonVariantsTable = document.getElementById('delete_button_'+i);
    deleteButtonVariantsTable.addEventListener('click', function (e) {
        e.preventDefault();
        deleteVariantsDiv('variant_tr_' + i);
    });
}

// удаление старого изображения. Тут model: Ларина модель ('Product')
// field: название поля mysql с изобр.('img' или 'header_mobile')
// id: записи из к-рой удаляется изобр. part_id - часть строки id скрытых инпутов
// нужно в категориях, где на одной странице 2 блока удаления изображения
function imageDelete(model, field ,id, part_id) {
    console.log(model, field ,id);

    Swal.fire({
        title: 'Вы уверены?',
        icon: 'warning',
        text: "Вы точно хотите это удалить?",
        //type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!'
    }).then((result) => {
        if(result.value) {

            let deletedImageModel = document.getElementById(part_id + '_model');
            deletedImageModel.value = model;
            let deletedImageField = document.getElementById(part_id + '_field');
            deletedImageField.value = field;
            let deletedImageId = document.getElementById(part_id + '_id');
            deletedImageId.value = id;
            console.log(deletedImageModel.value, deletedImageField.value, deletedImageId.value);
            document.getElementById('old_div_' + part_id).remove();

        }
    })

}
