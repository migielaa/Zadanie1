$(document).ready(() => {
    let form = $('.search-bar');

    form.submit((event) => {
        let inputValue = $('#search-input').val();
        let data = {
            value: inputValue
        };

        $.post('src/rest.php', data, (data) => {
            viewResults(data);
            changeElementsStyling();
        })
``
        event.preventDefault();
    })
});

const crawlOnUrlClick = (element) => {
    event.preventDefault();

    $(document).ready(() => {
        setTimeout(() => {
            console.log('elem innnerHtml:', $(element).html());
            console.log('element:', $(element));
            $('.data-input').val($(element).html());
            $('.submit-button').click();
        }, 1);

    });
}