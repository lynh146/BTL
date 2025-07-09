document.addEventListener("DOMContentLoaded", function () {
    const restaurantInput = document.getElementById("restaurant_name");
    const locationInput = document.getElementById("restaurant_location");

    restaurantInput.addEventListener("input", function () {
        const enteredName = restaurantInput.value.trim().toLowerCase();
        const match = restaurants.find(
            res => res.name.toLowerCase() === enteredName
        );

        if (match) {
            locationInput.value = match.location;
            locationInput.readOnly = true;
            locationInput.style.backgroundColor = "#f3f3f3";
        } else {
            locationInput.value = "";
            locationInput.readOnly = false;
            locationInput.style.backgroundColor = "#fff";
        }
    });
});
