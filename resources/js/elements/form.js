// ! do ajax... idem for date & disponibility
const selects = [...document.querySelectorAll('form.filter-auto select')];
if(selects.length > 0) {
  selects.forEach((select) => {
    select.addEventListener('change',function(e) {
      const filterForm = this.closest('form');
      const ajaxurl = filterForm.getAttribute('action');
      const data = {
        action: filterForm.querySelector('input[name=action]').value,
        nonce: filterForm.querySelector('input[name=nonce]').value,
        placeId: filterForm.querySelector('input[name=place_id]').value,
        date: filterForm.querySelector('input[name=tour_date]').value,
        thematic: filterForm.querySelector('select[name=tour_thematic]').value,
        accessibility: filterForm.querySelector('select[name=tour_accessibility]').value,
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
        console.log('response',response);

        if(!response.success) {
          alert(response.data);
          return;
        }

        document.querySelector('.guided_tours').innerHTML = response.data;
      })
    });
  })
}