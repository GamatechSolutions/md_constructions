(function (scope) {
	const UploaderOptions = {
		uploadUrl: '',
		deleteUrl: '',
		chunkSize: 1024 * 1024 * 2,
	};

	class FileUploader {
		constructor(options) {
			this.options = UploaderOptions;

			Object.assign(this.options, options);
		}

		upload(blob) {
			let uploadable = new File(this, blob);

			return uploadable.upload();
		}

		getOption(key) {
			return this.options[key];
		}

		getUUID() {
			return (`${1e7}-${1e3}-${4e3}-${8e3}-${1e11}`).replace(/[018]/g, (match) => {
				return (match ^ Math.random() * 16 >> match / 4).toString(16);
			});
		}

		getExtension(header) {
			switch (header) {
				case '49492A00':
				case '4D4D002A':
					return 'tiff';

				case '89504E47':
					return 'png';

				case '47494638':
					return 'gif';

				case 'FFD8FFE0':
				case 'FFD8FFE1':
				case 'FFD8FFE2':
				case 'FFD8FFE3':
				case 'FFD8FFE8':
					return 'jpeg';

				case '255044462D':
					return 'pdf';

				case '0000001466747970':
				case '0000001866747970':
				case '0000002066747970':
					return 'mp4';

				default:
					return 'unknown';
			}
		}
	}

	class File {
		constructor(uploader, blob) {
			this.uploader = uploader;
			this.blob = blob;
			this.reader = new FileReader();
			this.buffer = null;
			this.size = 0;
			this.header = '';
			this.name = '';
			this.extension = '';
			this.url = '';
		}

		async read() {
			return new Promise((resolve, reject) => {
				this.reader.onload = (event) => {
					resolve(this.reader.result);
				};

				this.reader.onerror = () => {
					reject('error reading file content');
				};

				this.reader.readAsArrayBuffer(this.blob);
			});
		}

		async prepare() {
			let result = await this.read();
			let lengths = [4, 5, 8];

			this.buffer = new Uint8Array(result);

			for (let i = 0; i < lengths.length; i++) {
				let signature = this.buffer.subarray(0, lengths[i]);

				this.header = '';

				for (let j = 0; j < lengths[i]; j++) {
					let code = signature[j].toString(16).toUpperCase();

					this.header += (code.length === 1) ? `0${code}` : code;
				}

				this.extension = this.uploader.getExtension(this.header);

				if (this.extension !== 'unknown') {
					break;
				}
			}
			this.size = this.buffer.length;
			this.name = `${this.uploader.getUUID()}.${this.extension}`;
		}

		async upload() {
			return new Promise(async (resolve, reject) => {
				try {
					await this.prepare();

					if (this.extension === 'unknown') {
						reject('unsupported file type');

						return;
					}

					let chunkSize = this.uploader.getOption('chunkSize');

					let helper = async (start) => {
						if (start >= this.size) {
							resolve(this);

							return;
						}

						try {
							let end = Math.min(start + chunkSize, this.size);

							let response = await this.uploadChunk(start, end);

							if (response.data.done == true) {
								this.url = response.data.url;
							}

							helper(end);
						}
						catch (exception) {
							reject('error uploading file chunk');
						}
					}

					helper(0);
				}
				catch (exception) {
					reject('error preparing file for upload');
				}
			});
		}

		async delete() {
			let data = new FormData();

			data.append('file', this.name);

			return axios.post(this.uploader.getOption('deleteUrl'), data, {
				headers: {

				},
				onUploadProgress: (event) => {

				}
			});
		}

		async uploadChunk(start, end) {
			let data = new FormData();

			data.append('file', new Blob([this.buffer.subarray(start, end)]), this.name);
			data.append('is_last', (end >= this.size));

			return axios.post(this.uploader.getOption('uploadUrl'), data, {
				headers: {
					'Content-Type': 'application/octet-stream'
				},
				onUploadProgress: (event) => {

				}
			});
		}
	}

	scope.FileUploader = FileUploader;
	scope.File = File;
})(window.Components || (window.Components = {}));