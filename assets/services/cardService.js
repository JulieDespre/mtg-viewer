export async function fetchAllCards(page = 1) {
    const response = await fetch(`/api/card/all/${page}`);
    if (!response.ok) throw new Error('Failed to fetch cards');
    const result = await response.json();
    return result;
}

export async function fetchCard(uuid) {
    const response = await fetch(`/api/card/${uuid}`);
    if (response.status === 404) return null;
    if (!response.ok) throw new Error('Failed to fetch card');
    const card = await response.json();
    card.text = card.text.replaceAll('\\n', '\n');
    return card;
}

export async function searchCards(name, setCode = '') {
    if (name.length < 3) {
        return [];
    }

    const response = await fetch(`/api/card/search/${name}/${setCode}`);
    if (!response.ok) throw new Error('Failed to search cards');
    const result = await response.json();
    return result;
}

export async function fetchSetCodes() {
    const response = await fetch('/api/card/set-codes');
    if (!response.ok) throw new Error('Failed to fetch set codes');
    const result = await response.json();
    return result;
}
