class Catalog extends React.Component {
    constructor() {
        super();

        this.state = {
            isWithSpinner: true,
            isHasError: false,
            errorMsg: "Something went wrong!"
        };

        this.addSpinner = this.addSpinner.bind(this);
        this.removeSpinner = this.removeSpinner.bind(this);
    }

    componentDidMount() {
        fetch(this.props.api)
            .then(res => res.json())
            .then(
                (result) => {
                    console.log(result);
                },
                (error) => {

                    this.setState({
                        isHasError: true,
                    });

                    const debug = this.props.debug;
                    if(debug) {
                        this.setState({
                            errorMsg: error,
                        });
                    }

                },
                this.removeSpinner()
            )
    }

    addSpinner() {
        this.setState({
            isWithSpinner: true
        });
    }

    removeSpinner() {
        this.setState({
            isWithSpinner: false
        });
    }

    render() {

        const isWithSpinner = this.state.isWithSpinner;
        const isHasError = this.state.isHasError;
        const errorMsg = this.state.errorMsg;
        let spinnerClassName;

        spinnerClassName = isWithSpinner ? "spinner-border" : "";

        return (
            <div className={`row center ${spinnerClassName}`}>
                {isHasError &&
                <div className='alert alert-danger'>{errorMsg.toString()}</div>
                }
            </div>
        )
    }
}

var element = document.getElementById("catalog-component");
ReactDOM.render(
    <Catalog api={element.dataset.api} debug={true} />,
    element
);