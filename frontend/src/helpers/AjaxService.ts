import axios from 'axios';

const apiBaseURL = 'http://localhost:8083/api/';

export class AjaxService {
	get<ParamT>(url: string, data?: any): Promise<ParamT> {
		return new Promise((resolve, reject) => {
			return axios
				.get(apiBaseURL + url, { params: data })
				.then((res) => resolve(res.data))
				.catch((err) => reject(err));
		});
	}

	post(url: string, data: any): Promise<any> {
		return new Promise((resolve, reject) => {
			return axios
				.post(apiBaseURL + url, data)
				.then((res) => resolve(res.data))
				.catch((err) => reject(err));
		});
	}
}
