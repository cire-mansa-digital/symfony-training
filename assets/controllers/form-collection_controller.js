import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = {
        addLabel: { type: String, default: "Ajouter un ingrédient" },
        deleteLabel: { type: String, default: "Supprimer" },
    };

    connect() {
        this.index = this.element.querySelectorAll(".collection-item").length;

        // Ajouter un bouton de suppression sur chaque élément existant
        this.element.querySelectorAll(".collection-item").forEach((item) => {
            this.addDeleteButton(item);
        });

        // Ajouter le bouton principal "+ Ajouter"
        if (!this.element.querySelector(".btn-add-collection")) {
            const btn = document.createElement("button");
            btn.setAttribute("type", "button");
            btn.className = " inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg shadow-sm transition cursor-pointer text-sm mt-3";
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> <span>${this.addLabelValue || "Ajouter un ingrédient"}</span>`;
            btn.addEventListener("click", this.addCollectionElement);

            this.element.append(btn);
        }
    }

    addCollectionElement = (e) => {
        e.preventDefault();

        const prototype = this.element.dataset.prototype;
        if (!prototype) {
            console.error("Attribut data-prototype manquant.");
            return;
        }

        const html = prototype.replaceAll("__name__", this.index);
        const fragment = document.createRange().createContextualFragment(html);

        const wrapper = document.createElement("div");
        wrapper.className = "collection-item p-4 bg-gray-50 rounded-xl border border-gray-200 shadow-sm relative";
        wrapper.appendChild(fragment);

        this.addDeleteButton(wrapper);
        this.index++;

        // Insertion avant le bouton "+ Ajouter"
        const addBtn = this.element.querySelector(".btn-add-collection");
        if (addBtn) {
            this.element.insertBefore(wrapper, addBtn);
        } else {
            this.element.appendChild(wrapper);
        }
    };

    addDeleteButton = (item) => {
        if (!(item instanceof HTMLElement) || item.querySelector(".btn-delete-item")) {
            return;
        }

        const btn = document.createElement("button");
        btn.setAttribute("type", "button");
        btn.className = "btn-delete-item inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition text-xs cursor-pointer mt-3";
        btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> <span>${this.deleteLabelValue || "Supprimer"}</span>`;

        btn.addEventListener("click", (e) => {
            e.preventDefault();
            item.remove();
        });

        item.appendChild(btn);
    };
}
