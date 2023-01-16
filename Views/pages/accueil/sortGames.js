const filtresDropdown = document.getElementById("filtres");
const [...jeux] = document.querySelectorAll(".jeu-container");
const [...nomJeu] = document.querySelectorAll(".nom-jeu");
const gameSearchInput = document.getElementById("recherche_jeu");

// nomJeu.forEach((jeu) => {
//   console.log(jeu.parentElement.parentElement.parentElement);
// });

const updateListDropdown = () => {
  jeux.map((jeu) => {
    if (jeu.classList.contains("switched")) {
      jeu.classList.replace("switched", "switch");
      jeu.style.order = null;
    } else {
      jeu.classList.add("switch");
      jeu.style.order = null;
    }
    const classes = jeu.className
      .split(" ")
      .filter((c) => !c.startsWith("order-"));
    jeu.className = classes.join(" ").trim();
  });
};

const refillList = () => {
  jeux.map((jeu) => {
    if (jeu.classList.contains("switch")) {
      jeu.classList.replace("switch", "switched");
    } else {
      jeu.classList.add("switched");
    }
    jeu.style.order = null;
  });
};

const loadListDropdown = () => {
  let i = 0;
  jeux.map((jeu) => {
    if (jeu.classList.contains(filtresDropdown.value)) {
      jeu.classList.add(`order-${i}`);
      jeu.classList.replace("switch", "switched");
      i++;
    } else {
      jeu.style.order = 99;
    }
  });
};

const updateListSearch = () => {
  jeux.map((jeu) => {
    if (jeu.classList.contains("switched")) {
      jeu.classList.replace("switched", "switch");
    } else {
      jeu.classList.add("switch");
    }
    jeu.style.order = null;
  });
};

const loadListSearch = () => {
  let i = 0;
  jeux.forEach((jeu) => {
    if (
      jeu.dataset.nom.startsWith(gameSearchInput.value) ||
      jeu.dataset.nom === gameSearchInput.value
    ) {
      jeu.classList.replace("switch", "switched");
      jeu.classList.add(`order-${i}`);
      console.log(jeu);
      i++;
    } else {
      jeu.style.order = 99;
      jeu.classList.replace("switched", "switch");
    }
  });
};

filtresDropdown.addEventListener("change", () => {
  gameSearchInput.value = "";
  updateListDropdown();
  setTimeout(loadListDropdown, 500);
});

gameSearchInput.addEventListener("keyup", () => {
  gameSearchInput.value.length >= 2 ? loadListSearch() : refillList();
  console.log(gameSearchInput.value.length);
});
