document.addEventListener("DOMContentLoaded", function(){

const about = document.querySelector(".about-container");
const img = document.querySelector(".about img");

window.addEventListener("scroll", ()=>{

const rect = about.getBoundingClientRect();

// 🔥 MASUK VIEW
if(rect.top < window.innerHeight - 100 && rect.bottom > 100){
about.classList.add("show");
} 
// 🔥 KELUAR VIEW
else{
about.classList.remove("show");
}

});

});