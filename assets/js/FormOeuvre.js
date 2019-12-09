export default class FormOeuvre {
    constructor() {
        this. selection = document.querySelector('select').addEventListener('change', this.change.bind(this));
    }

         async change(ev){




            const category = ev.target.value;
            console.log(category);

            const request = new Request(`/oeuvres/filter`, {
                method: 'post',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    category: category
                })
            });
                const query =  await fetch(request);
                const response = await query.json();

            this.displayOeuvre(response.data);
        }

    displayOeuvre(data){
        let html = '';
        data.map(  oeuvre => {
            html += `
<div class="w-25 m-1">
<a href="/oeuvres/${oeuvre.id}">
             <div class="card bg-dark text-white">
                <img class="card-img" src="/img/oeuvre/${oeuvre.image}" alt="Card image">
                <div class="card-img-overlay">
                    <h5 class="card-title">${ oeuvre.name }</h5>
                </div>
            </div></a></div>
            `;
        });

        const listOeuvres = document.querySelector(".listoeuvre");
        listOeuvres.innerHTML = html;
    }
}