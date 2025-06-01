class MediaModalItem {
    modal;
    data;
    thumbnailElements;

    element;

    constructor(modal, data) {
        this.modal = modal;
        this.data = data;
        this.thumbnailElements = [];

        this.element = {};
    }
    
    getDistanceFromCurrentItem() {
        return Math.abs(this.modal.index - this.modal.getIndexForItem(this));
    }

    isImage() {
        return true;
    }

    show() {
        const thumbnail = this.getFirstThumbnailElement();
        this.showElement('img', { src: thumbnail.src, alt: thumbnail.alt });

        if(this.isImage()) {
            this.loadImage(img => {
                this.showElement('img', { src: img.currentSrc ?? thumbnail.src, alt: thumbnail.alt });
            })
        }
    }
    
    loadImage(callback) {
        const img = document.createElement('img');
        img.onload = () => {
            if(this !== this.modal.getCurrentItem()) return;
            callback(img);
        }
        img.srcset = this.getImageSrcSet();
    }

    showElement(tag, attributes) {
        this.element = { tag, attributes };
    }

    getImageSrcSet() {
        return Object.values(this.data.sources).map(source => `${source.url} ${source.width}w`).join(', ');
    }

    getThumbnailElements() {
        return this.thumbnailElements;
    }

    getFirstThumbnailElement() {
        return this.thumbnailElements[0];
    }

    addThumbnailElement(element) {
        return this.thumbnailElements.push(element);
    }
}