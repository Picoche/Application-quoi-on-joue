const btnDel = document.querySelectorAll(".btnDelete");
const btnEdit = document.querySelectorAll(".btnEdit");

const mapJeux = new Map();
function assignBtns() {
  const jeux = document.querySelectorAll(".jeu-container");
  jeux.forEach((jeu) => {
    btnDel.forEach((btn) => {
      if (jeu.dataset.nom === btn.dataset.nom) {
        mapJeux.set(btn.dataset.nom, jeu);
      }
    });
  });
}
assignBtns();

async function deleteJeu(btn) {
  try {
    const data = await fetchData({
      function: "delJeu",
      param: btn,
    });
    console.log(data.jeu);
    console.log(mapJeux);
    console.log(mapJeux.get(data.jeu));
    mapJeux.get(data.jeu).classList.add("switch");
  } catch (error) {
    console.log(error);
  }
}

async function fetchData(body) {
  const requestOptions = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
  };

  const request = await fetch(
    "./Views/pages/accueil/queryDelAccueil.php",
    requestOptions
  );
  return request.json();
}

btnDel.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    deleteJeu(e.target.dataset.nom);
  });
});
