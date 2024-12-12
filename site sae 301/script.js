
function getLocation(address) {
    fetch(`api/google_maps.php?address=${encodeURIComponent(address)}`)
        .then(response => response.json())
        .then(data => {
            console.log(data);
        });
}
