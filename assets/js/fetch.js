const container = document.getElementById("container");

fetch("/investimentos/json")
  .then((response) => response.json())
  .then((response) => {
    console.log(response);

    array.forEach((response) => {
      container.innerHTML = response.id;
    });
  });
