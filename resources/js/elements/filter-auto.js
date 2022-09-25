// AJAX auto filter guided tours
function displayFilterGuidedTours(e) {
    const filterForm = this.closest('form');
    const ajaxurl = filterForm.getAttribute('action');
    const data = {
        action: filterForm.querySelector('input[name=action]').value,
        nonce: filterForm.querySelector('input[name=nonce]').value,
        placeId: filterForm.querySelector('input[name=place_id]').value,
        date: filterForm.querySelector('input[name=tour_date]').value,
        thematic: filterForm.querySelector('select[name=tour_thematic]').value,
        constraint: filterForm.querySelector('select[name=tour_constraint]').value,
        // todo select disponibilité
    };

    console.log('ajax', ajaxurl);
    console.log('data', data);

    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Cache-Control': 'no-cache',
        },
        body: new URLSearchParams(data),
    })
        .then(response => response.json())
        .then(response => {
            console.log('response', response);

            if (!response.success) {
                alert(response.data);
                return;
            }

            document.querySelector('#not-found-guided-tours')?.remove();
            document.querySelector('.load-more-guided-tours-btn').classList.remove('hidden');
            document.querySelector('.load-more-guided-tours-btn').dataset.page = 1;
            document.querySelector('.guided_tours').innerHTML = response.data;
        })
}
// .input can be input:date and selects
const inputs = [...document.querySelectorAll('form.filter-auto .auto-filter-input')];
if (inputs.length > 0) {
    inputs.forEach((input) => {
        input.addEventListener('change', displayFilterGuidedTours);
    })
}