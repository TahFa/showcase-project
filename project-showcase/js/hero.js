const words = [
"Project Mahasiswa",
"Inovasi Teknologi",
"AI & Web Development"
];

let i = 0;
let j = 0;
let currentWord = "";
let isDeleting = false;

function type(){

currentWord = words[i];

if(isDeleting){
j--;
}else{
j++;
}

document.getElementById("typing").textContent =
currentWord.substring(0,j);

if(!isDeleting && j === currentWord.length){

isDeleting = true;
setTimeout(type,1200);
return;

}

if(isDeleting && j === 0){

isDeleting = false;
i++;

if(i === words.length){
i = 0;
}

}

setTimeout(type,isDeleting ? 60 : 120);

}

type();

particlesJS("particles-js", {
  "particles": {
    "number": {
      "value": 60
    },
    "size": {
      "value": 3
    },
    "color": {
      "value": "#6366f1"
    },
    "line_linked": {
      "enable": true,
      "opacity": 0.2
    },
    "move": {
      "speed": 1
    }
  }
});