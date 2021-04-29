for (let i = 0; i < document.links.length; i++) {
    if (document.links[i].className === "logo") {
        continue;
    }

    if (document.links[i].href === document.URL) {
        document.links[i].className = "active";
    } else {
        document.links[i].className = "inactive";
    }
}