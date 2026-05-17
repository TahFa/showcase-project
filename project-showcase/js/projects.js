document.addEventListener("DOMContentLoaded", () => {

const cards = document.querySelectorAll(".cover-card");
const next = document.querySelector(".next");
const prev = document.querySelector(".prev");

let index = Math.floor(cards.length / 2);

function update() {

    cards.forEach((card, i) => {
        card.className = "cover-card";

        let pos = (i - index + cards.length) % cards.length;

        if (pos === 0) {
            card.classList.add("center");
        } 
        else if (pos === 1) {
            card.classList.add("right1");
        } 
        else if (pos === 2) {
            card.classList.add("right2");
        } 
        else if (pos === cards.length - 1) {
            card.classList.add("left1");
        } 
        else if (pos === cards.length - 2) {
            card.classList.add("left2");
        }
    });

}

next.onclick = () => {
    index = (index + 1) % cards.length;
    update();
};

prev.onclick = () => {
    index = (index - 1 + cards.length) % cards.length;
    update();
};

setInterval(() => {
    next.click();
}, 3000);

update();

});