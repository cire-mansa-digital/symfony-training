import { stringify } from "postcss";
import { Controller } from "@hotwired/stimulus";


export default class extends Controller {
    static values = {
        addLabel:String,
        deleteLabel:String
    }

    connect() {
        this.index = this.element.childElementCount
        const btn = document.createElement("button");
        btn.setAttribute("class", "inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-amber-600 to-orange-500 text-white font-semibold rounded-full shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5");
        btn.innerText = this.addLabelValue || "Ajouter élément";
        btn.setAttribute("type", "button");
        btn.addEventListener("click", this.addElement);
        this.element.childNodes.forEach(this.addDeleteButton)
        this.element.append(btn)

    }



    addElement = (e) => {
        e.preventDefault();
        const element = document.createRange().createContextualFragment(
            this.element.dataset['prototype'].replaceAll('_name_',this.index)
        ).firstElementChild
        this.addDeleteButton(element)
        this.index++
        e.currentTarget.insertAdjacentElement('beforebegin', element)
    };

    addDeleteButton = (item)=>{
        this.index = this.element.childElementCount
        const btn = document.createElement("button");
        btn.setAttribute("class", "inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-full shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5");
        btn.innerText = this.deleteLabelValue || "Supprimer ";
        btn.setAttribute("type", "button");
        item.append(btn)
        btn.addEventListener('click', e=>{
            e.preventDefault
            item.remove()
        })
    }
}

