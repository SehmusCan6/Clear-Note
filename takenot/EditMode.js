document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function () {
        const noteDiv = this.closest(".note");
        const noteId = noteDiv.dataset.noteId;
        const title = noteDiv.querySelector("h3").innerText;
        const content = noteDiv.querySelector("p").innerText;


        document.getElementById("editTitle").value = title;
        document.getElementById("editContent").value = content;
        document.getElementById("editNoteId").value = noteId;


        document.getElementById("editSidebar").style.display = "block";
    });
});

function closeEdit() {
    document.getElementById("editSidebar").style.display = "none";
}

