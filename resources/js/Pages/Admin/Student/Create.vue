<template>
    <Head title="Create Student" />
    <AdminLayout>
        <div>
        <h1 class="text-xl font-bold mb-4">Create Student</h1>
        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <!-- Student ID -->
            <div>
                <label for="student_id" class="block">Student ID:</label>
                <input type="text" v-model="form.student_id" id="student_id" class="input" required />
                <span v-if="form.errors.student_id" class="text-red-500">{{ form.errors.student_id }}</span>
            </div>
            
            <!-- Form Number -->
            <div>
                <label for="form_number" class="block">Form Number:</label>
                <input type="text" v-model="form.form_number" id="form_number" class="input" required />
                <span v-if="form.errors.form_number" class="text-red-500">{{ form.errors.form_number }}</span>
            </div>
        
            <!-- Name (Bangla) -->
            <div>
                <label for="name_bn" class="block">Name (Bangla):</label>
                <input type="text" v-model="form.name_bn" id="name_bn" class="input" required />
                <span v-if="form.errors.name_bn" class="text-red-500">{{ form.errors.name_bn }}</span>
            </div>
        
            <!-- Name (English) -->
            <div>
                <label for="name_en" class="block">Name (English):</label>
                <input type="text" v-model="form.name_en" id="name_en" class="input" required />
                <span v-if="form.errors.name_en" class="text-red-500">{{ form.errors.name_en }}</span>
            </div>
        
            <!-- Date of Birth -->
            <div>
                <label for="date_of_birth" class="block">Date of Birth:</label>
                <input type="date" v-model="form.date_of_birth" id="date_of_birth" class="input" required />
                <span v-if="form.errors.date_of_birth" class="text-red-500">{{ form.errors.date_of_birth }}</span>
            </div>
        
            <!-- Gender -->
            <div>
                <label for="gender" class="block">Gender:</label>
                <select v-model="form.gender" id="gender" class="input" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
                </select>
                <span v-if="form.errors.gender" class="text-red-500">{{ form.errors.gender }}</span>
            </div>
        
            <!-- Nationality -->
            <div>
                <label for="nationality" class="block">Nationality:</label>
                <input type="text" v-model="form.nationality" id="nationality" class="input" required />
                <span v-if="form.errors.nationality" class="text-red-500">{{ form.errors.nationality }}</span>
            </div>
        
            <!-- Religion -->
            <div>
                <label for="religion" class="block">Religion:</label>
                <input type="text" v-model="form.religion" id="religion" class="input" required />
                <span v-if="form.errors.religion" class="text-red-500">{{ form.errors.religion }}</span>
            </div>
        
            <!-- Blood Group -->
            <div>
                <label for="blood_group" class="block">Blood Group:</label>
                <input type="text" v-model="form.blood_group" id="blood_group" class="input" required />
                <span v-if="form.errors.blood_group" class="text-red-500">{{ form.errors.blood_group }}</span>
            </div>
        
            <!-- Class -->
            <div>
                <label for="class_id" class="block">Class:</label>
                <select v-model="form.class_id" id="class_id" class="input" required>
                <option value="">Select Class</option>
                <option v-for="schoolClass in classes" :key="schoolClass.id" :value="schoolClass.id">{{ schoolClass.name }}</option>
                </select>
                <span v-if="form.errors.class_id" class="text-red-500">{{ form.errors.class_id }}</span>
            </div>
        
            <!-- Section -->
            <div>
                <label for="section_id" class="block">Section:</label>
                <select v-model="form.section_id" id="section_id" class="input" required>
                <option value="">Select Section</option>
                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                </select>
                <span v-if="form.errors.section_id" class="text-red-500">{{ form.errors.section_id }}</span>
            </div>
        
            <!-- Parent -->
            <div>
                <label for="parent_id" class="block">Parent:</label>
                <select v-model="form.parent_id" id="parent_id" class="input" required>
                <option value="">Select Parent</option>
                <option v-for="parent in parents" :key="parent.id" :value="parent.id">{{ parent.name }}</option>
                </select>
                <span v-if="form.errors.parent_id" class="text-red-500">{{ form.errors.parent_id }}</span>
            </div>
        
            <!-- Mother's Name (Bangla) -->
            <div>
                <label for="mother_name_bn" class="block">Mother's Name (Bangla):</label>
                <input type="text" v-model="form.mother_name_bn" id="mother_name_bn" class="input" required />
                <span v-if="form.errors.mother_name_bn" class="text-red-500">{{ form.errors.mother_name_bn }}</span>
            </div>
        
            <!-- Mother's Name (English) -->
            <div>
                <label for="mother_name_en" class="block">Mother's Name (English):</label>
                <input type="text" v-model="form.mother_name_en" id="mother_name_en" class="input" required />
                <span v-if="form.errors.mother_name_en" class="text-red-500">{{ form.errors.mother_name_en }}</span>
            </div>
        
            <!-- Mother's Mobile -->
            <div>
                <label for="mother_mobile" class="block">Mother's Mobile:</label>
                <input type="text" v-model="form.mother_mobile" id="mother_mobile" class="input" required />
                <span v-if="form.errors.mother_mobile" class="text-red-500">{{ form.errors.mother_mobile }}</span>
            </div>
        
            <!-- Father's Name (Bangla) -->
            <div>
                <label for="father_name_bn" class="block">Father's Name (Bangla):</label>
                <input type="text" v-model="form.father_name_bn" id="father_name_bn" class="input" required />
                <span v-if="form.errors.father_name_bn" class="text-red-500">{{ form.errors.father_name_bn }}</span>
            </div>
        
            <!-- Father's Name (English) -->
            <div>
                <label for="father_name_en" class="block">Father's Name (English):</label>
                <input type="text" v-model="form.father_name_en" id="father_name_en" class="input" required />
                <span v-if="form.errors.father_name_en" class="text-red-500">{{ form.errors.father_name_en }}</span>
            </div>
        
            <!-- Father's Mobile -->
            <div>
                <label for="father_mobile" class="block">Father's Mobile:</label>
                <input type="text" v-model="form.father_mobile" id="father_mobile" class="input" required />
                <span v-if="form.errors.father_mobile" class="text-red-500">{{ form.errors.father_mobile }}</span>
            </div>
        
            <!-- Present Address -->
            <div>
                <label for="present_address_village" class="block">Present Address (Village):</label>
                <input type="text" v-model="form.present_address_village" id="present_address_village" class="input" required />
                <span v-if="form.errors.present_address_village" class="text-red-500">{{ form.errors.present_address_village }}</span>
            </div>
        
            <!-- Permanent Address -->
            <div>
                <label for="permanent_address_village" class="block">Permanent Address (Village):</label>
                <input type="text" v-model="form.permanent_address_village" id="permanent_address_village" class="input" required />
                <span v-if="form.errors.permanent_address_village" class="text-red-500">{{ form.errors.permanent_address_village }}</span>
            </div>
        
            <!-- Information Correct -->
            <div>
                <label for="information_correct" class="block">Is the information correct?</label>
                <input type="checkbox" v-model="form.information_correct" id="information_correct" />
            </div>
        
            <!-- Submit Button -->
            <button type="submit" class="btn">Create</button>
            </div>
        </form>
        </div>
    </AdminLayout>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { useForm, Link, Head } from '@inertiajs/vue3';  
  import AdminLayout from '@/Layouts/AdminLayout.vue';

  
  export default {
    props: {
      classes: Array,
      sections: Array,
      parents: Array,
    },
    setup(props) {
      const form = useForm({
        student_id: '',
        form_number: '',
        name_bn: '',
        name_en: '',
        date_of_birth: '',
        gender: '',
        nationality: '',
        religion: '',
        blood_group: '',
        class_id: '',
        section_id: '',
        parent_id: '',
        mother_name_bn: '',
        mother_name_en: '',
        mother_mobile: '',
        father_name_bn: '',
        father_name_en: '',
        father_mobile: '',
        present_address_village: '',
        permanent_address_village: '',
        information_correct: false,
      });
  
      const submit = () => {
        form.post('/admin/students'); // Update the URL as necessary
      };
  
      return {
        form,
        submit,
      };
    },
  };
  </script>
  
  <style scoped>
  .input {
    border: 1px solid #ccc;
    padding: 8px;
    border-radius: 4px;
    width: 100%;
  }
  
  .btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .btn:hover {
    background-color: #45a049;
  }
  
  .text-red-500 {
    color: red;
  }
  </style>
  