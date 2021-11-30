import React from 'react';
import Cookies from 'universal-cookie';
import { If, Then, ElseIf, Else } from 'react-if-elseif-else-render';

class AdminIndex extends React.Component {
    constructor(props) {
        super(props);
        this.fileInput = React.createRef();
        this.handleSubmit = this.handleSubmit.bind(this);
        this.state = {
            error: null,
            isLoaded: false,
        };
    }

    handleSubmit(event) {
        const cookies = new Cookies();
        event.preventDefault();
        let formData = new FormData();
        formData.append('file', this.fileInput.current.files[0]);
        formData.append('type', event.target.type.value);
        axios.post('http://192.168.1.139:8000/api/admin/importCSV',
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': 'Bearer ' + cookies.get('accessToken')
                }
            }
        ).then((data) => {
            console.log('SUCCESS!!' + data);
        })
            .catch((err) => {
                this.setState({
                    isLoaded: true,
                    error: err.response.data.Err_Desc
                });
            });
    }
    render() {
        return (
            <div>
                <form onSubmit={this.handleSubmit}>
                    <label>
                        File:
                        <input name="file" type="file" accept=".csv" ref={this.fileInput} />
                        <select name="type">
                            <option value="Needies">Needies</option>
                            <option value="OnlineTransaction">OnlineTransaction</option>
                            <option value="OfflineTransaction">OfflineTransaction</option>
                        </select>
                    </label>
                    <input type="submit" value="Submit" />
                </form>
                <If condition={this.state.error != null}>
                    <Then>
                        <div>Error: {this.state.error}</div>
                    </Then>
                    <ElseIf condition={!this.state.isLoaded}>
                        <div>Loading...</div>
                    </ElseIf>
                    <Else>
                        <div>File Imported Successfully</div>
                    </Else>
                </If>

            </div>
        );

    }

}

export default AdminIndex
