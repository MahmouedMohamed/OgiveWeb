import React from 'react'


function DonationForm() {

    return (
        <div>
            <form>
                <fieldset>
                    <label>
                        <p>Name</p>
                        <input name="name" />
                    </label>
                </fieldset>
                <button type="submit">Submit</button>
            </form>        </div>
    );
}

export default DonationForm;