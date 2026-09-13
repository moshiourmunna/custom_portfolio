import { useForm } from '@inertiajs/react';

function Row({ inquiry }) {
  const form = useForm({ status: inquiry.status, note: inquiry.note || '' });
  return (
    <tr>
      <td>{inquiry.company || inquiry.name}</td>
      <td>{inquiry.interest}</td>
      <td>{inquiry.message}</td>
      <td>{inquiry.received_on}</td>
      <td>
        <select value={form.data.status} onChange={(event) => form.setData('status', event.target.value)}>
          <option value="new">new</option>
          <option value="in_progress">in_progress</option>
          <option value="closed">closed</option>
        </select>
      </td>
      <td><input value={form.data.note} onChange={(event) => form.setData('note', event.target.value)} /></td>
      <td><button type="button" onClick={() => form.put(`/admin/inquiries/${inquiry.id}`)}>Save</button></td>
    </tr>
  );
}

export default function Inquiries({ inquiries }) {
  return (
    <>
      <header className="admin-top"><h1>Inquiries</h1></header>
      <div className="admin-content">
        <table className="dash-table">
          <thead><tr><th>Company</th><th>Interest</th><th>Message</th><th>Date</th><th>Status</th><th>Note</th><th></th></tr></thead>
          <tbody>{inquiries.map((inquiry) => <Row key={inquiry.id} inquiry={inquiry} />)}</tbody>
        </table>
      </div>
    </>
  );
}
