const contact = document.querySelector(".contact-content");

window.addEventListener("scroll", ()=>{

const rect = contact.getBoundingClientRect();

if(rect.top < window.innerHeight - 100){
contact.classList.add("show");
}else{
contact.classList.remove("show");
}

});