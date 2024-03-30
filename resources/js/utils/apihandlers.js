




class ApiHandler {

    constructor() {
        this.token = localStorage.getItem('accessToken');
        this.api = axios.create({
            baseURL: 'http://localhost:8000',
            timeout: 10000,
            withCredentials: true,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.token}`
            },
        });
    }
    // GET request
    async get(url, params) {
        try {
            const response = await this.api.get(url, { params });
            return response;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // POST request
    async post(url, data) {
        try {
            const response = await this.api.post(url, data);
            return response;
        } catch (error) {
            throw this.handleError(error);
        }
    }



    // POST request with file upload
    async postWithFile(url, data, file) {
        try {
            // Create a FormData object to handle the file upload
            const formData = new FormData();
            formData.append('original_file_name', file); // 'original_file_name' should match the server's expected file parameter name

            // Append other data to the FormData object if needed
            for (const [key, value] of Object.entries(data)) {
                if (data.hasOwnProperty(key)) {
                    formData.append(key, value.toString());
                }
            }

            const response = await this.api.post(url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data', // Set the content type for file upload
                },
                ...config
            });

            return response;
        } catch (error) {
            throw this.handleError(error);
        }
    }


    // PUT request
    async put(url, data) {
        try {
            const response = await this.api.put(url, data);
            return response;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // PUT request
    async patch(url, data) {
        try {
            const response = await this.api.patch(url, data);
            return response;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // DELETE request
    async delete(url) {
        try {
            const response = await this.api.delete(url);
            return response;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async promises(urls) {
        try {
            // Create an array of promises for each URL
            const promises = urls.map((url) => this.api.get(url));

            // Use axios.all() to resolve all promises simultaneously
            const responses = await axios.all(promises);

            // Extract and return the response data from each resolved promise
            return responses.map((response) => response.data);
        } catch (error) {
            throw this.handleError(error);
        }
    }


    // Handle API errors
    handleError(error) {
        console.log('error', error);
        const error_response = {
            'message': error?.response?.data?.error ? error?.response?.data?.error : error?.message,
            'status': error?.response?.status ? error?.response?.status : 400
        }
        throw error_response;
    }

}

export default ApiHandler;