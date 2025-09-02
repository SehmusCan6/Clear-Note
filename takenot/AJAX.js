// CREATE NOTE
document.getElementById("noteForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const title = document.getElementById("noteTitle").value.trim();
    const content = document.getElementById("noteContent").value.trim();

    if (!title || !content) {
        alert("Please fill in both the title and content.");
        return;
    }

    fetch("savenotes.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const noteList = document.getElementById("noteList");
                const newNote = document.createElement("div");
                newNote.classList.add("note");
                newNote.setAttribute("data-note-id", data.id);
                newNote.innerHTML = `
                    <div class="icons">
                        <span onclick="toggleFavorite(this)">⭐</span>
                        <span onclick="toggleFlag(this)">🚩</span>
                    </div>
                    <h3>${data.title}</h3>
                    <p>${data.content}</p>
                    <small>${data.created_at}</small>
                    <button class="edit-btn" onclick="openEdit(${data.id})">✏️ Edit</button>
                `;
                noteList.prepend(newNote);

                document.getElementById("noteTitle").value = "";
                document.getElementById("noteContent").value = "";
            } else {
                alert("❌ Failed to save note.");
            }
        })
        .catch(err => {
            console.error("Save Error:", err);
            alert("❌ Server error while saving.");
        });
});


// OPEN EDIT PANEL
function openEdit(id) {
    const note = document.querySelector(`.note[data-note-id="${id}"]`);
    if (!note) return;

    const title = note.querySelector("h3").innerText;
    const content = note.querySelector("p").innerText;

    document.getElementById("editNoteId").value = id;
    document.getElementById("editTitle").value = title;
    document.getElementById("editContent").value = content;

    document.getElementById("editPanel").classList.add("active");
}

// CLOSE EDIT PANEL
function closeEdit() {
    document.getElementById("editPanel").classList.remove("active");
    document.getElementById("editNoteId").value = "";
    document.getElementById("editTitle").value = "";
    document.getElementById("editContent").value = "";
}

// SAVE EDITED NOTE
function saveEdit() {
    const id = document.getElementById("editNoteId").value;
    const title = document.getElementById("editTitle").value.trim();
    const content = document.getElementById("editContent").value.trim();

    if (!title || !content) {
        alert("Please fill in both the title and content.");
        return;
    }

    fetch("update_note.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}&title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const note = document.querySelector(`.note[data-note-id="${id}"]`);
                note.querySelector("h3").innerText = data.title;
                note.querySelector("p").innerText = data.content;
                note.querySelector("small").innerText = data.updated_at;

                closeEdit();
            } else {
                alert("❌ Failed to update note.");
            }
        })
        .catch(err => {
            console.error("Update Error:", err);
            alert("❌ Server error while updating.");
        });
}

// DELETE NOTE
function deleteNote() {
    const id = document.getElementById("editNoteId").value;

    if (!confirm("Are you sure you want to delete this note?")) return;

    fetch("delete_note.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${id}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const note = document.querySelector(`.note[data-note-id="${id}"]`);
                if (note) note.remove();
                closeEdit();
            } else {
                alert("❌ Failed to delete note.");
            }
        })
        .catch(err => {
            console.error("Delete Error:", err);
            alert("❌ Server error while deleting.");
        });
}

// TOGGLE FAVORITE & FLAG
function toggleFavorite(el) {
    el.classList.toggle("active");
    el.closest(".note").classList.toggle("favorited");
}

function toggleFlag(el) {
    el.classList.toggle("active");
    el.closest(".note").classList.toggle("flagged");
}
