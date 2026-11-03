'use strict';

document.querySelectorAll('form[data-confirm]').forEach(function (form) {
	form.addEventListener('submit', function (event) {
		if (!window.confirm(form.dataset.confirm)) {
			event.preventDefault();
		}
	});
});

const image_input = document.querySelector('#image');
const image_preview = document.querySelector('#image-preview');

if (image_input && image_preview) {
	const original_image = image_preview.src;
	let preview_url;

	image_input.addEventListener('change', function () {
		if (preview_url) {
			URL.revokeObjectURL(preview_url);
		}

		const file = image_input.files[0];
		if (file && ['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
			preview_url = URL.createObjectURL(file);
			image_preview.src = preview_url;
		} else {
			image_preview.src = original_image;
		}
	});
}
