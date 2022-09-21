

// AJAX auto filter guided tours
function displayFilterGuidedTours(e) {
  const filterForm = this.closest('form');
  const ajaxurl = filterForm.getAttribute('action');
  const data = {
    action : filterForm.querySelector('input[name=action]').value,
    nonce : filterForm.querySelector('input[name=nonce]').value,
    placeId : filterForm.querySelector('input[name=place_id]').value,
    date : filterForm.querySelector('input[name=tour_date]').value,
    thematic : filterForm.querySelector('select[name=tour_thematic]').value,
    constraint : filterForm.querySelector('select[name=tour_constraint]').value,
    // todo select disponibilité
  };

  console.log('ajax', ajaxurl);
  console.log('data', data);

  fetch(ajaxurl, {
    method : 'POST',
    headers : {
      'Content-Type' : 'application/x-www-form-urlencoded',
      'Cache-Control' : 'no-cache',
    },
    body : new URLSearchParams(data),
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
}
// .input can be input:date and selects
const inputs = [...document.querySelectorAll('form.filter-auto .input')];
if(inputs.length > 0) {
  inputs.forEach((input) => {
    input.addEventListener('change',displayFilterGuidedTours);
  })
}

function displayMorePlaces() {
  const ajaxurl = this.dataset.ajaxurl;
  const data = {
    action : this.dataset.action,
    nonce :  this.dataset.nonce,
    place_type : this.dataset.place_type,
    deparment : this.dataset.deparment,
    s : this.dataset.s,
    page : Number(this.dataset.page) + 1,
  };

  console.log('ajax', ajaxurl);
  console.log('data', data);

  fetch(ajaxurl, {
    method : 'POST',
    headers : {
      'Content-Type' : 'application/x-www-form-urlencoded',
      'Cache-Control' : 'no-cache',
    },
    body : new URLSearchParams(data),
  })
  .then(response => response.json())
  .then(response => {
    console.log('response',response);

    if(!response.success) {
      alert(response.data);
      return;
    }

    if(response.data == '') {
      this.classList.add('hidden');
      document.querySelector('.archive-places-list').insertAdjacentHTML('afterend','<p class="text-center">Aucun autre lieu trouvé pour ses critères.</p>');
    } else {
      this.dataset.page = data.page;
      document.querySelector('.archive-places-list').insertAdjacentHTML('beforeend',response.data);
    }

  })
}
// AJAX view more places 
const loadMoreBtn = document.querySelector('.load-more-places-btn');
loadMoreBtn?.addEventListener('click',displayMorePlaces);