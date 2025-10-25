// Load menu data using AJAX
function loadMenuData() {
    const xhr = new XMLHttpRequest();
    
    xhr.onload = function() {
        const data = JSON.parse(xhr.responseText);
        displayMenu(data);
    };
    
    xhr.open('GET', 'data/menu-data.json');
    xhr.send();
}

// Display menu in grid
function displayMenu(data) {    
    const menuGrid = document.getElementById('menu-grid');
    
    data.dishes.forEach(dish => {
        const card = document.createElement('div');
        card.className = 'menu-card';
        
        card.innerHTML = `
            <img src="${dish.image}" alt="${dish.name}" class="card-image">
            <div class="card-content">
                <div class="card-header">
                    <h3 class="dish-name">${dish.name}</h3>
                    <span class="price">${dish.price}</span>
                </div>
                <div class="cuisine-category">
                    <span class="category-badge category-${dish.category}">${dish.category}</span>
                    <span class="cuisine-type">${dish.cuisine}</span>
                </div>
                <p class="dish-description">${dish.description}</p>
                <div class="ingredients-section">
                    <h4>Ingredients:</h4>
                    <ul class="ingredients-list">
                        ${dish.ingredients.map(i => `<li>${i}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
        
        menuGrid.appendChild(card);
    });
    
    document.getElementById('loading').style.display = 'none';
    document.getElementById('menu-grid').style.display = 'grid';
}

// Load when page loads
document.addEventListener('DOMContentLoaded', loadMenuData);