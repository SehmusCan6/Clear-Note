const form = document.getElementById("planForm");
const saveMsg = document.getElementById("formSaveMsg");

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    const title = formData.get("title").trim();
    const date = formData.get("date");
    const planId = formData.get("plan_id");

    if (!title) {
        alert("Please enter a title.");
        return;
    }

    const isUpdate = planId !== "";
    const url = isUpdate ? "update_plan.php" : "save_plan.php";

    fetch(url, {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (saveMsg) {
                    saveMsg.textContent = isUpdate ? "✅ Plan updated successfully!" : "✔️ Plan saved successfully!";
                    saveMsg.style.display = "block";
                }

                if (isUpdate) {
                    const existing = document.querySelector(`.plan-box[data-id="${planId}"]`);
                    if (existing) {
                        existing.setAttribute("data-title", data.title);
                        existing.setAttribute("data-subject", data.subject);
                        existing.setAttribute("data-description", data.description);
                        existing.setAttribute("data-date", data.date);

                        existing.innerHTML = `
                        <div class="icons">
                            <span class="star" onclick="toggleFavorite(this)">⭐</span>
                            <span class="flag" onclick="toggleFlag(this)">🚩</span>
                        </div>
                        <h4>${data.title}</h4>
                        ${data.subject ? `<p><strong>Subject:</strong> ${data.subject}</p>` : ""}
                        ${data.description ? `<p>${data.description}</p>` : ""}
                        <p style="font-size: 12px; color: #999;">📅 ${data.date}</p>
                        <button class="edit-btn" style="margin-top: 10px;">🖊️ Edit</button>
                    `;
                    }
                } else {
                    const newBox = document.createElement("div");
                    newBox.classList.add("plan-box");

                    newBox.setAttribute("data-id", data.id);
                    newBox.setAttribute("data-title", data.title);
                    newBox.setAttribute("data-subject", data.subject);
                    newBox.setAttribute("data-description", data.description);
                    newBox.setAttribute("data-date", data.date);

                    newBox.innerHTML = `
                    <div class="icons">
                        <span class="star" onclick="toggleFavorite(this)">⭐</span>
                        <span class="flag" onclick="toggleFlag(this)">🚩</span>
                    </div>
                    <h4>${data.title}</h4>
                    ${data.subject ? `<p><strong>Subject:</strong> ${data.subject}</p>` : ""}
                    ${data.description ? `<p>${data.description}</p>` : ""}
                    <p style="font-size: 12px; color: #999;">📅 ${data.date}</p>
                    <button class="edit-btn" style="margin-top: 10px;">🖊️ Edit</button>
                `;

                    document.getElementById("planList").prepend(newBox);
                }

                form.reset();
                document.getElementById("planId").value = "";
                document.getElementById("selectedDate").textContent = "";
                setTimeout(() => {
                    saveMsg.style.display = "none";
                }, 1500);
                planPanel.classList.remove("active");
            }
        });
});

// EDIT BUTTON LOGIC
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit-btn")) {
        const box = e.target.closest(".plan-box");
        const title = box.dataset.title;
        const subject = box.dataset.subject;
        const description = box.dataset.description;
        const date = box.dataset.date;
        const id = box.dataset.id;

        document.querySelector("input[name='title']").value = title;
        document.querySelector("input[name='subject']").value = subject;
        document.querySelector("textarea[name='description']").value = description;
        document.querySelector("input[name='date']").value = date;
        document.getElementById("formDate").value = date;
        document.getElementById("planId").value = id;

        document.getElementById("selectedDate").textContent = `Selected Date: ${date}`;
        planPanel.classList.add("active");
    }
});

// DELETE FUNCTION
function handleDeleteNote() {
    const id = document.getElementById("planId").value;

    if (!id) {
        alert("❌ No plan ID found to delete!");
        return;
    }

    const confirmDelete = confirm("Are you sure you want to delete this plan?");
    if (!confirmDelete) return;

    fetch("delete_plan.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `id=${encodeURIComponent(id)}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const box = document.querySelector(`.plan-box[data-id="${id}"]`);
                if (box) box.remove();

                form.reset();
                document.getElementById("planId").value = "";
                document.getElementById("selectedDate").textContent = "";
                planPanel.classList.remove("active");
            } else {
                alert("❌ Deletion failed: " + (data.message || "Unknown error"));
            }
        })
        .catch(err => {
            console.error("Delete error:", err);
            alert("❌ Server error.");
        });
}
