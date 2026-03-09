@verbatim
final class StoreUserRequestValidator extends Validator
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
@endverbatim
